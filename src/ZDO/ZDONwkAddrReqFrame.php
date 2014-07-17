<?php

namespace Munisense\Zigbee\ZDO;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\MuniZigbeeException;

class ZDONwkAddrReqFrame
  {
  const REQUEST_TYPE_SINGLE = 0x00;
  const REQUEST_TYPE_EXTENDED = 0x01;

  private $ieee_address = "0";
  private $request_type = 0x00;
  private $start_index = 0x00;

  public function __construct($frame = null)
    {
    if($frame !== null)
      $this->setFrame($frame);
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

  public function displayFrame()
    {
    return Buffer::displayOctetString($this->getFrame());
    }

  public function setIeeeAddress($ieee_address)
    {
    try
      {
      $buffer = "";
      Buffer::packEui64($buffer, $ieee_address);
      $this->ieee_address = Buffer::unpackEui64($buffer);
      }
    catch(MuniZigbeeException $e)
      {
      throw new MuniZigbeeException("Invalid ieee address");
      }
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
    $output .= "|- IeeeAddr    : ".$this->displayIeeeAddress().PHP_EOL;
    $output .= ($this->isStartIndexPresent() ? "|" : "`")."- RequestType : ".$this->displayRequestType().PHP_EOL;
    if($this->isStartIndexPresent())
      $output .= "`- StartIndex  : ".$this->displayStartIndex().PHP_EOL;

    return $output;
    }
  }

