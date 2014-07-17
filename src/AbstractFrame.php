<?php

namespace Munisense\Zigbee;

/**
 * Class AbstractFrame
 *
 * Container for all the shared methods between the different Zigbee Frames
 *
 * @package Munisense\Zigbee
 */
abstract class AbstractFrame implements IFrame
  {
  public function __construct($frame = null)
    {
    if($frame !== null)
      $this->setFrame($frame);
    }

  public function displayFrame()
    {
    return Buffer::displayOctetString($this->getFrame());
    }
  }