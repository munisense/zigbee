<?php

namespace Munisense\Zigbee\ZCL\General;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\MuniZigbeeException;
use Munisense\Zigbee\ZCL\ZCLStatus;

class AttributeStatusRecord extends AbstractFrame
  {
  const DIRECTION_SERVER_TO_CLIENT = 0x00;
  const DIRECTION_CLIENT_TO_SERVER = 0x01;

  private $status;
  private $direction = self::DIRECTION_SERVER_TO_CLIENT;
  private $attribute_id;

  public static function construct(ZCLStatus $status, $direction, $attribute_id)
    {
    $element = new self;
    $element->setStatus($status);
    $element->setDirection($direction);
    $element->setAttributeId($attribute_id);
    return $element;
    }

  public function consumeFrame(&$frame)
    {
    $this->setStatus(Buffer::unpackInt8u($frame));
    $this->setDirection(Buffer::unpackInt8u($frame));
    $this->setAttributeId(Buffer::unpackInt16u($frame));
    }

  public function setFrame($frame)
    {
    $this->consumeFrame($frame);
    }

  public function getFrame()
    {
    $frame = "";

    Buffer::packInt8u($frame, $this->getStatus());
    Buffer::packInt8u($frame, $this->getDirection());
    Buffer::packInt16u($frame, $this->getAttributeId());

    return $frame;
    }

  /**
   * @param int $status
   */
  public function setStatus($status)
    {
    $this->status = $status;
    }

  /**
   * @return int
   */
  public function getStatus()
    {
    return $this->status;
    }

  public function displayStatus()
    {
    return ZCLStatus::displayStatus($this->status);
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

  public function __toString()
    {
    return "Status: ".$this->displayStatus().", Direction: ".$this->displayDirection().", AttributeId: ".$this->displayAttributeId();
    }
  }

