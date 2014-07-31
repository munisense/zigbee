<?php

namespace Munisense\Zigbee\ZDO\Discovery;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\MuniZigbeeException;
use Munisense\Zigbee\ZDO\Command;
use Munisense\Zigbee\ZDO\IZDOCommandFrame;

/**
 * Class IEEEAddrReqCommand
 *
 * @package Munisense\Zigbee\ZDO\Discovery
 *
 * The IEEE_addr_req is generated from a LocalDevice wishing to inquire as to the
 * 64-bit IEEE address of the Remote Devicebased on their known 16-bit address.
 * The destination addressing on this command shall be unicast.
 */
class IEEEAddrReqCommand extends AbstractFrame implements IZDOCommandFrame
  {
  private $nwk_address;

  const REQUEST_TYPE_SINGLE = 0x00;
  const REQUEST_TYPE_EXTENDED = 0x01;
  private $request_type = self::REQUEST_TYPE_SINGLE;

  private $start_index = 0x00;

  public static function constructSingle($nwk_address)
    {
    $frame = new self;
    $frame->setRequestType(self::REQUEST_TYPE_SINGLE);
    $frame->setNwkAddress($nwk_address);
    return $frame;
    }

  public static function constructExtended($nwk_address, $start_index = 0x00)
    {
    $frame = new self;
    $frame->setRequestType(self::REQUEST_TYPE_EXTENDED);
    $frame->setNwkAddress($nwk_address);
    $frame->setStartIndex($start_index);
    return $frame;
    }

  public function setFrame($frame)
    {
    $this->setNwkAddress(Buffer::unpackInt16u($frame));
    $this->setRequestType(Buffer::unpackInt8u($frame));
    if($this->isStartIndexPresent())
      $this->setStartIndex(Buffer::unpackInt8u($frame));
    }

  public function getFrame()
    {
    $frame = "";

    Buffer::packInt16u($frame, $this->getNwkAddress());
    Buffer::packInt8u($frame, $this->getRequestType());
    if($this->isStartIndexPresent())
      Buffer::packInt8u($frame, $this->getStartIndex());

    return $frame;
    }

  public function setNwkAddress($nwk_address)
    {
    if($nwk_address >= 0x0000 && $nwk_address <= 0xffff)
      $this->nwk_address = $nwk_address;
    else
      throw new MuniZigbeeException("Invalid nwk address");
    }

  public function getNwkAddress()
    {
    return $this->nwk_address;
    }

  public function displayNwkAddress()
    {
    return Buffer::displayInt16u($this->getNwkAddress());
    }

  public function setRequestType($request_type)
    {
    if(!in_array($request_type, array(self::REQUEST_TYPE_SINGLE, self::REQUEST_TYPE_EXTENDED)))
      throw new MuniZigbeeException("Invalid request type");

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
      throw new MuniZigbeeException("Invalid start index");

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
    $output .= "|- NwkAddr    : ".$this->displayNwkAddress().PHP_EOL;
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
    return Command::COMMAND_IEEE_ADDR_REQ;
    }
  }

