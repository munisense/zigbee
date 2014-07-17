<?php

namespace Munisense\Zigbee\ZCL\General;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\MuniZigbeeException;
use Munisense\Zigbee\IFrame;

class AttributeIdentifier extends AbstractFrame
  {
  private $attribute_id = 0x0000;

  public static function construct($attribute_id)
    {
    $element = new self;
    $element->setAttributeId($attribute_id);
    return $element;
    }

  public function setFrame($frame)
    {
    $this->setAttributeId(Buffer::unpackInt16u($frame));
    }

  public function getFrame()
    {
    $frame = "";

    Buffer::packInt16u($frame, $this->getAttributeId());

    return $frame;
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
    return "AttributeId: ".$this->displayAttributeId();
    }
  }

