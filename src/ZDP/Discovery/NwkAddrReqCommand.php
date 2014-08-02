<?php

namespace Munisense\Zigbee\ZDP\Discovery;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\ZigbeeException;
use Munisense\Zigbee\ZDP\Command;
use Munisense\Zigbee\ZDP\IZDPCommandFrame;

/**
 * Class NwkAddrReqCommand
 * @package Munisense\Zigbee\ZDP\Discovery
 *
 * The NWK_addr_req is generated from a Local Device wishing to inquire as to the
 * 16-bit address of the Remote Device based on its known IEEE address. The
 * destination addressing on this command shall be unicast or broadcast to all
 * devices for which macRxOnWhenIdle = TRUE.
 */
class NwkAddrReqCommand extends AbstractFrame implements IZDPCommandFrame
  {
  private $ieee_address;

  const REQUEST_TYPE_SINGLE = 0x00;
  const REQUEST_TYPE_EXTENDED = 0x01;
  private $request_type = self::REQUEST_TYPE_SINGLE;

  private $start_index = 0x00;

  public static function constructSingle($ieee_address)
    {
    $frame = new self;
    $frame->setRequestType(self::REQUEST_TYPE_SINGLE);
    $frame->setIeeeAddress($ieee_address);
    return $frame;
    }

  public static function constructExtended($ieee_address, $start_index = 0x00)
    {
    $frame = new self;
    $frame->setRequestType(self::REQUEST_TYPE_EXTENDED);
    $frame->setIeeeAddress($ieee_address);
    $frame->setStartIndex($start_index);
    return $frame;
    }

  public function setFrame($frame)
    {
    $this->setIeeeAddress(Buffer::unpackEui64($frame));
    $this->setRequestType(Buffer::unpackInt8u($frame));
    if($this->isStartIndexPresent())
      $this->setStartIndex(Buffer::unpackInt8u($frame));
    }

  public function getFrame()
    {
    $frame = "";

    Buffer::packEui64($frame, $this->getIeeeAddress());
    Buffer::packInt8u($frame, $this->getRequestType());
    if($this->isStartIndexPresent())
      Buffer::packInt8u($frame, $this->getStartIndex());

    return $frame;
    }

  public function setIeeeAddress($ieee_address)
    {
    if($ieee_address > 0x0000000000000000 && $ieee_address <= 0xffffffffffffffff)
      $this->ieee_address = $ieee_address;
    else
      throw new ZigbeeException("Invalid IEEE Address");
    }

  public function getIeeeAddress()
    {
    return $this->ieee_address;
    }

  public function displayIeeeAddress()
    {
    return Buffer::displayEui64($this->getIeeeAddress());
    }

  public function setRequestType($request_type)
    {
    if(!in_array($request_type, array(self::REQUEST_TYPE_SINGLE, self::REQUEST_TYPE_EXTENDED)))
      throw new ZigbeeException("Invalid request type");

    $this->request_type = $request_type;
    }

  public function getRequestType()
    {
    return $this->request_type;
    }

  public function displayRequestType()
    {
    $request_type = $this->getRequestType();
    switch($request_type)
      {
      case self::REQUEST_TYPE_SINGLE: $output = "Single Device Response"; break;
      case self::REQUEST_TYPE_EXTENDED: $output = "Extended Response"; break;
      default: $output = "unknown"; break;
      }

    return sprintf("%s (0x%02x)", $output, $request_type);
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

  private function isStartIndexPresent()
    {
    if($this->getRequestType() === self::REQUEST_TYPE_EXTENDED)
      return true;

    return false;
    }

  public function __toString()
    {
    $output = __CLASS__." (length: ".strlen($this->getFrame()).")".PHP_EOL;
    $output .= "|- IeeeAddr    : ".$this->displayIeeeAddress().PHP_EOL;
    $output .= ($this->isStartIndexPresent() ? "|" : "`")."- RequestType : ".$this->displayRequestType().PHP_EOL;
    if($this->isStartIndexPresent())
      $output .= "`- StartIndex  : ".$this->displayStartIndex().PHP_EOL;

    return $output;
    }

  /**
   * Returns the Cluster ID of this frame
   * @return int
   */
  public function getClusterId()
    {
    return Command::COMMAND_NWK_ADDR_REQ;
    }
  }

