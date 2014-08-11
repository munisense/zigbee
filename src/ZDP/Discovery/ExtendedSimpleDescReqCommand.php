<?php

namespace Munisense\Zigbee\ZDP\Discovery;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\ZigbeeException;
use Munisense\Zigbee\ZDP\Command;

/**
 * Class ExtendedSimpleDescReqCommand
 *
 * @package Munisense\Zigbee\ZDP\Discovery
 *
 * The Extended_Simple_Desc_req command is generated from a local device
 * wishing to inquire as to the simple descriptor of a remote device on a specified
 * endpoint.
 */
class ExtendedSimpleDescReqCommand extends SimpleDescReqCommand
  {
  private $start_index = 0;

  public static function constructExtended($nwk_addr_of_interest, $endpoint, $start_index)
    {
    $frame = new self;
    $frame->setNwkAddressOfInterest($nwk_addr_of_interest);
    $frame->setEndpoint($endpoint);
    $frame->setStartIndex($start_index);
    return $frame;
    }

  public function setFrame($frame)
    {
    $this->setNwkAddressOfInterest(Buffer::unpackInt16u($frame));
    $this->setEndpoint(Buffer::unpackInt8u($frame));
    $this->setStartIndex(Buffer::unpackInt8u($frame));
    }

  public function getFrame()
    {
    $frame = parent::getFrame();
    Buffer::packInt8u($frame, $this->getStartIndex());
    return $frame;
    }

  public function __toString()
    {
    $output = __CLASS__." (length: ".strlen($this->getFrame()).")".PHP_EOL;
    $output .= "|- NwkAddr     : ".$this->displayNwkAddressOfInterest().PHP_EOL;
    $output .= "|- Endpoint    : ".$this->displayEndpoint().PHP_EOL;
    $output .= "`- Start Index : ".$this->displayStartIndex().PHP_EOL;

    return $output;
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

  /**
   * Returns the Cluster ID of this frame
   * @return int
   */
  public function getClusterId()
    {
    return Command::COMMAND_EXTENDED_SIMPLE_DESC_REQ;
    }
  }

