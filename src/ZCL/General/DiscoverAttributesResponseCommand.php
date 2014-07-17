<?php

namespace Munisense\Zigbee\ZCL\General;

use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\ZCL\IZCLCommandFrame;
use Munisense\Zigbee\ZCL\ZCLFrame;

/**
 * Class DiscoverAttributesResponseCommand
 * @package Munisense\Zigbee\ZCL\General
 *
 * TODO Needs a __toString()
 */
class DiscoverAttributesResponseCommand extends AbstractFrame implements IZCLCommandFrame
  {
  /**
   * @var bool $discovery_complete
   */
  private $discovery_complete;

  /**
   * @var AttributeInformation[] $discovery_complete
   */
  private $attributes;

  public static function construct($discovery_complete, array $attributes)
    {
    $frame = new DiscoverAttributesResponseCommand();
    $frame->setDiscoveryComplete($discovery_complete);
    $frame->setAttributes($attributes);
    return $frame;
    }

  function getFrame()
    {
    $frame = "";

    Buffer::packInt8u($frame, $this->getDiscoveryComplete() ? 1 : 0);

    foreach($this->attributes as $attribute)
      $frame .= $attribute->getFrame();

    return $frame;
    }

  function setFrame($frame)
    {
    $this->setDiscoveryComplete(Buffer::unpackInt8u($frame) == 1);

    while(strlen($frame))
      {
      $attribute_information = new AttributeInformation();
      $attribute_information->consumeFrame($frame);
      $this->addAttributeInformation($attribute_information);
      }
    }

  /**
   * @param AttributeInformation[] $attributes
   */
  public function setAttributes($attributes)
    {
    $this->attributes = [];
    foreach($attributes as $attribute_information)
      $this->addAttributeInformation($attribute_information);
    }

  public function addAttributeInformation(AttributeInformation $attribute_information)
    {
    $this->attributes[] = $attribute_information;
    }

  /**
   * @return AttributeInformation[]
   */
  public function getAttributes()
    {
    return $this->attributes;
    }

  /**
   * @param boolean $discovery_complete
   */
  public function setDiscoveryComplete($discovery_complete)
    {
    $this->discovery_complete = $discovery_complete;
    }

  /**
   * @return boolean
   */
  public function getDiscoveryComplete()
    {
    return $this->discovery_complete;
    }

  /**
   * Returns the Command ID of this frame
   * @return int
   */
  public function getCommandId()
    {
    return GeneralCommand::DISCOVER_ATTRIBUTES_RESPONSE;
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