<?php

namespace Munisense\Zigbee\ZDP\Network;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\ZigbeeException;
use Munisense\Zigbee\ZDP\IZDPCommandFrame;
use Munisense\Zigbee\ZDP\Status;

/**
 * Base class for some of the simpler ZDP Network calls that only have a StartIndex as payload.
 *
 * @package Munisense\Zigbee\ZDP\Discovery
 */
abstract class AbstractStartIndexReqCommand extends AbstractFrame implements IZDPCommandFrame
  {
  private $start_index;

  public static function construct($start_index)
    {
    $frame = new static;
    $frame->setStartIndex($start_index);
    return $frame;
    }

  public function setFrame($frame)
    {
    $this->setStartIndex(Buffer::unpackInt8u($frame));
    }

  public function getFrame()
    {
    $frame = "";
    Buffer::packInt8u($frame, $this->getStartIndex());
    return $frame;
    }

  public function setStartIndex($start_index)
    {
    $start_index = intval($start_index);
    if($start_index < 0x00 || $start_index > 0xff)
      throw new ZigbeeException("Invalid start index");

    $this->start_index = $start_index;
    }

  public function getStartIndex()
    {
    return $this->start_index;
    }

  public function displayStartIndex()
    {
    return sprintf("0x%02x", $this->getStartIndex());
    }

  public function __toString()
    {
    $output = __CLASS__." (length: ".strlen($this->getFrame()).")".PHP_EOL;
    $output .= "`- StartIndex  : ".$this->displayStartIndex().PHP_EOL;
    return $output;
    }
  }
