<?php

namespace Munisense\Zigbee\ZCL\General;

use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\ZCL\IZCLCommandFrame;
use Munisense\Zigbee\ZCL\ZCLFrame;

class ReadAttributesCommand extends AbstractFrame implements IZCLCommandFrame
  {
  /**
   * @var AttributeIdentifier[]
   */
  private $attribute_identifiers = array();

  public static function construct(array $elements = array())
    {
    $frame = new self;
    $frame->attribute_identifiers = $elements;
    return $frame;
    }

  public function setFrame($frame)
    {
    while(strlen($frame))
      {
      $attribute_identifier = new AttributeIdentifier();
      $attribute_identifier->setAttributeId(Buffer::unpackInt16u($frame));
      $this->attribute_identifiers[] = $attribute_identifier;
      }
    }

  public function getFrame()
    {
    $frame = "";

    foreach($this->attribute_identifiers as $attribute_identifier)
      $frame .= $attribute_identifier->getFrame();

    return $frame;
    }

  public function setAttributeIdentifiers(array $attribute_identifiers)
    {
    $this->attribute_identifiers = [];
    foreach($attribute_identifiers as $attribute_identifier)
      $this->addAttributeIdentifier($attribute_identifier);
    }

  public function getAttributeIdentifiers()
    {
    return $this->attribute_identifiers;
    }

  public function addAttributeIdentifier(AttributeIdentifier $attribute_identifier)
    {
    $this->attribute_identifiers[] = $attribute_identifier;
    }

  public function __toString()
    {
    $output =  __CLASS__." (count: ".count($this->getAttributeIdentifiers()).", length: ".strlen($this->getFrame()).")".PHP_EOL;
    $read_attributes_elements = $this->getAttributeIdentifiers();
    $read_attributes_elements_count = count($read_attributes_elements);
    foreach($read_attributes_elements as $key => $read_attributes_element)
      $output .= ($key + 1 == $read_attributes_elements_count ? "`" : "|")."- ".$read_attributes_element.PHP_EOL;

    return $output;
    }


  /**
   * Returns the Command ID of this frame
   * @return int
   */
  public function getCommandId()
    {
    return GeneralCommand::READ_ATTRIBUTES;
    }

  /**
   * Returns the Frame Type of this frame
   * @return int
   */
  public function getFrameType()
    {
    return ZCLFrame::FRAME_TYPE_PROFILE_WIDE;
    }
  }

