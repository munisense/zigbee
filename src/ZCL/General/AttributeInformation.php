<?php

namespace Munisense\Zigbee\ZCL\General;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\MuniZigbeeException;

class AttributeInformation extends AbstractFrame
  {
  private $attribute_id;
  private $datatype_id;

  public static function construct($attribute_id, $datatype_id)
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

