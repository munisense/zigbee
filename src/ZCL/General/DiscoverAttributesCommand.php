<?php

namespace Munisense\Zigbee\ZCL\General;

use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\ZCL\IZCLCommandFrame;
use Munisense\Zigbee\ZCL\ZCLFrame;

/**
 * Class DiscoverAttributesCommand
 * @package Munisense\Zigbee\ZCL\General
 *
 * TODO Needs a __toString()
 */
class DiscoverAttributesCommand extends AbstractFrame implements IZCLCommandFrame
  {
  private $start_attribute_identifier;
  private $maximum_attribute_identifiers;

  public static function construct($start_attribute_identifier, $maximum_attribute_identifiers)
    {
    $frame = new DiscoverAttributesCommand();
    $frame->setStartAttributeIdentifier($start_attribute_identifier);
    $frame->setMaximumAttributeIdentifiers($maximum_attribute_identifiers);
    return $frame;
    }

  function getFrame()
    {
    $frame = "";

    Buffer::packInt16u($frame, $this->getStartAttributeIdentifier());
    Buffer::packInt8u($frame, $this->getMaximumAttributeIdentifiers());

    return $frame;
    }

  function setFrame($frame)
    {
    $this->setStartAttributeIdentifier(Buffer::unpackInt16u($frame));
    $this->setMaximumAttributeIdentifiers(Buffer::unpackInt8u($frame));
    }

  public function setStartAttributeIdentifier($start_attribute_identifier)
    {
    $this->start_attribute_identifier = $start_attribute_identifier;
    }

  public function setMaximumAttributeIdentifiers($maximum_attribute_identifiers)
    {
    $this->maximum_attribute_identifiers = $maximum_attribute_identifiers;
    }

  public function getMaximumAttributeIdentifiers()
    {
    return $this->maximum_attribute_identifiers;
    }

  public function getStartAttributeIdentifier()
    {
    return $this->start_attribute_identifier;
    }

  /**
   * Returns the Command ID of this frame
   * @return int
   */
  public function getCommandId()
    {
    return GeneralCommand::DISCOVER_ATTRIBUTES;
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