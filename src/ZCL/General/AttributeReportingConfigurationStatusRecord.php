<?php

namespace Munisense\Zigbee\ZCL\General;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\MuniZigbeeException;
use Munisense\Zigbee\ZCL\ZCLStatus;

/**
 * Class AttributeReportingConfigurationStatusRecord
 * @package Munisense\Zigbee\ZCL\General
 *
 * TODO Implementation is partly done
 */
class AttributeReportingConfigurationStatusRecord extends AbstractFrame
  {
  private $status;
  const DIRECTION_SERVER_TO_CLIENT = 0x00;
  const DIRECTION_CLIENT_TO_SERVER = 0x01;

  private $direction = self::DIRECTION_SERVER_TO_CLIENT;
  private $attribute_id;
  private $datatype_id;
  private $minimum_reporting_interval;
  private $maximum_reporting_interval;
  private $reportable_change;
  private $timeout_period;

  public static function construct($status, $direction, $attribute_id, $datatype_id, $minimum_reporting_interval,
                                   $maximum_reporting_interval, $reportable_change, $timeout_period)
    {
    $element = new self;
    $element->setAttributeId($attribute_id);
    $element->setDatatypeId($datatype_id);
    return $element;
    }

  public function consumeFrame(&$frame)
    {
    $this->setAttributeId(Buffer::unpackInt16u($frame));
    $this->setDatatypeId(Buffer::unpackInt8u($frame));
    }

  public function setFrame($frame)
    {
    $this->consumeFrame($frame);
    }

  public function getFrame()
    {
    $frame = "";

    Buffer::packInt16u($frame, $this->getAttributeId());
    Buffer::packInt8u($frame, $this->getDatatypeId());

    return $frame;
    }

  /**
   * @param int $status
   * @throws MuniZigbeeException
   */
  public function setStatus($status)
    {
    $status = intval($status);
    if($status < 0x00 || $status > 0xff)
      throw new MuniZigbeeException("Invalid status");

    $this->status = $status;
    }

  /**
   * @return mixed
   */
  public function getStatus()
    {
    return $this->status;
    }

  public function displayStatus()
    {
    return ZCLStatus::displayStatus($this->getStatus());
    }

  public function setDirection($direction)
    {
    $direction = intval($direction);
    if($direction < 0x00 || $direction > 0x01)
      throw new MuniZigbeeException("Invalid direction");

    $this->direction = $direction;
    }

  public function getDirection()
    {
    return $this->direction;
    }

  public function displayDirection()
    {
    return sprintf("0x%02x", $this->getDirection());
    }

  public function setAttributeId($attribute_id)
    {
    $attribute_id = intval($attribute_id);
    if($attribute_id < 0x00 || $attribute_id > 0xffff)
      throw new MuniZigbeeException("Invalid attribute id");

    $this->attribute_id = $attribute_id;
    }

  public function getAttributeId()
    {
    return $this->attribute_id;
    }

  public function displayAttributeId()
    {
    return sprintf("0x%04x", $this->getAttributeId());
    }

  public function setDatatypeId($datatype_id)
    {
    $datatype_id = intval($datatype_id);
    if($datatype_id < 0x00 || $datatype_id > 0xff)
      throw new MuniZigbeeException("Invalid datatype id");

    $this->datatype_id = $datatype_id;
    }

  public function getDatatypeId()
    {
    return $this->datatype_id;
    }

  public function displayDatatypeId()
    {
    return sprintf("0x%02x", $this->getDatatypeId());
    }

  public function __toString()
    {
    return "AttributeId: ".$this->displayAttributeId().", DatatypeId: ".$this->displayDatatypeId();
    }
  }

