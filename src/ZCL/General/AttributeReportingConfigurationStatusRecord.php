<?php

namespace Munisense\Zigbee\ZCL\General;

use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\ZigbeeException;
use Munisense\Zigbee\ZCL\ZCLStatus;

/**
 * Class AttributeReportingConfigurationStatusRecord
 * @package Munisense\Zigbee\ZCL\General
 */
class AttributeReportingConfigurationStatusRecord extends AttributeReportingConfigurationRecord
  {
  private $status;

  /**
   * AttributeReportingConfigurationStatusRecord is a AttributeReportingConfigurationRecord
   * prepended with a status field.
   *
   * @param AttributeReportingConfigurationRecord $parent
   * @return AttributeReportingConfigurationStatusRecord
   */
  public static function constructSuccess(AttributeReportingConfigurationRecord $parent)
    {
    $element = new self;
    $element->setStatus(ZCLStatus::SUCCESS);

    $element->setParentFrame($parent);

    return $element;
    }

  /**
   * If there is an error code, we do not need the full AttributeReportingConfigurationRecord
   *
   * @param $status
   * @param $direction
   * @param $attribute_id
   * @return AttributeReportingConfigurationStatusRecord
   */
  public static function constructWithError($status, $direction, $attribute_id)
    {
    $element = new self;
    $element->setStatus($status);
    $element->setDirection($direction);
    $element->setAttributeId($attribute_id);

    return $element;
    }

  protected function setParentFrame(AttributeReportingConfigurationRecord $parent)
    {
    $parent_frame = $parent->getFrame();
    parent::consumeFrame($parent_frame);
    }

  public function consumeFrame(&$frame)
    {
    $this->setStatus(Buffer::unpackInt8u($frame));
    parent::consumeFrame($frame);
    }


  public function getFrame()
    {
    $frame = "";

    Buffer::packInt8u($frame, $this->getStatus());

    if($this->getStatus() == ZCLStatus::SUCCESS)
      {
      $frame .= parent::getFrame();
      }
    // If the status field is not set to SUCCESS, all fields except the direction and
    // attribute identifier fields shall be omitted.
    else
      {
      Buffer::packInt8u($frame, $this->getDirection());
      Buffer::packInt16u($frame, $this->getAttributeId());
      }

    return $frame;
    }

  /**
   * If the attribute is not implemented on the sender or receiver of the command,
   * whichever is relevant (depending on direction), this field shall be set to
   * UNSUPPORTED_ATTRIBUTE. If the attribute is supported, but is not capable of
   * being reported, this field shall be set to UNREPORTABLE_ATTRIBUTE.
   * Otherwise, this field shall be set to SUCCESS.
   *
   * @param int $status
   * @throws ZigbeeException
   */
  public function setStatus($status)
    {
    $status = intval($status);
    if(!in_array($status, [ZCLStatus::UNSUPPORTED_ATTRIBUTE, ZCLStatus::UNREPORTABLE_ATTRIBUTE, ZCLStatus::SUCCESS]))
      throw new ZigbeeException("Invalid status");

    $this->status = $status;
    }

  /**
   * @return int Status
   */
  public function getStatus()
    {
    return $this->status;
    }

  public function displayStatus()
    {
    return ZCLStatus::displayStatus($this->getStatus());
    }

  public function __toString()
    {
    return "Status: ".$this->displayStatus().", ".parent::__toString();
    }
  }

