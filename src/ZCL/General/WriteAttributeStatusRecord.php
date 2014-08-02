<?php

namespace Munisense\Zigbee\ZCL\General;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\ZigbeeException;
use Munisense\Zigbee\ZCL\ZCLStatus;

class WriteAttributeStatusRecord extends AbstractFrame
  {
  private $status;
  private $attribute_id;

  public static function construct($status, $attribute_id)
    {
    $element = new self;
    $element->setStatus($status);
    $element->setAttributeId($attribute_id);
    return $element;
    }

  public function consumeFrame(&$frame)
    {
    $this->setStatus(Buffer::unpackInt8u($frame));
    $this->setAttributeId(Buffer::unpackInt16u($frame));
    }

  public function setFrame($frame)
    {
    $this->consumeFrame($frame);

    if(strlen($frame) > 0)
      throw new ZigbeeException("Still data left in frame buffer");
    }

  public function getFrame()
    {
    $frame = "";

    Buffer::packInt8u($frame, $this->getStatus());
    Buffer::packInt16u($frame, $this->getAttributeId());

    return $frame;
    }

  public function setAttributeId($attribute_id)
    {
    $attribute_id = intval($attribute_id);
    if($attribute_id < 0x0000 || $attribute_id > 0xffff)
      throw new ZigbeeException("Invalid attribute id");

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

  /**
   * @param int $status
   * @throws ZigbeeException
   */
  public function setStatus($status)
    {
    $status = intval($status);
    if($status < 0x00 || $status > 0xff)
    throw new ZigbeeException("Invalid status");

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

  public function __toString()
    {
    return "AttributeId: ".$this->displayAttributeId().", Status: ".$this->displayStatus();
    }
  }
