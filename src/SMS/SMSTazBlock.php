<?php

namespace Munisense\Zigbee\SMS;
use Munisense\Zigbee\APS\APSFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\ZigbeeException;
use Munisense\Zigbee\IFrame;

class SMSTazBlock implements IFrame
  {
  const MILLENNIUM_EPOCH = 946684800;

  const REQUEST_TIMESTAMP_NOT_PRESENT = 0x00;
  const REQUEST_TIMESTAMP_IS_PRESENT = 0x01;

  const EXECUTE_TIMESTAMP_NOT_PRESENT = 0x00;
  const EXECUTE_TIMESTAMP_IS_PRESENT = 0x01;

  const END_TIMESTAMP_NOT_PRESENT = 0x00;
  const END_TIMESTAMP_IS_PRESENT = 0x01;

  const APS_FORMAT_NORMAL = 0x00;
  const APS_FORMAT_SHORT_ZCL = 0x01;
  const APS_FORMAT_SHORT_ZDP = 0x02;
  const APS_FORMAT_RESERVED = 0x03;

  private $request_timestamp_present = self::REQUEST_TIMESTAMP_NOT_PRESENT;
  private $execute_timestamp_present = self::EXECUTE_TIMESTAMP_NOT_PRESENT;
  private $end_timestamp_present = self::END_TIMESTAMP_NOT_PRESENT;
  private $aps_format = self::APS_FORMAT_NORMAL;

  private $request_timestamp = 0;
  private $execute_timestamp = 0;
  private $end_timestamp = 0;

  private $payload = "";
  private $taz_header_reserved;

  public function __construct($frame = null)
    {
    if($frame !== null)
      $this->setFrame($frame);
    }

  public function setFrame($frame)
    {
    $this->setTazHeader(Buffer::unpackInt8u($frame));
    $length = Buffer::unpackInt8u($frame);

    if($length !== strlen($frame))
      throw new ZigbeeException("Invalid length in length field");

    if($this->getRequestTimestampPresent())
      $this->setRequestTimestamp(Buffer::unpackUTC($frame) + self::MILLENNIUM_EPOCH);

    if($this->getExecuteTimestampPresent())
      $this->setExecuteTimestamp(Buffer::unpackUTC($frame) + self::MILLENNIUM_EPOCH);

    if($this->getEndTimestampPresent())
      $this->setEndTimestamp(Buffer::unpackUTC($frame) + self::MILLENNIUM_EPOCH);

    $this->setPayload($frame);
    }

  public function getFrame()
    {
    $subframe = "";
    if($this->getRequestTimestampPresent())
      Buffer::packUTC($subframe, $this->getRequestTimestamp() - self::MILLENNIUM_EPOCH);

    if($this->getExecuteTimestampPresent())
      Buffer::packUTC($subframe, $this->getExecuteTimestamp() - self::MILLENNIUM_EPOCH);

    if($this->getEndTimestampPresent())
      Buffer::packUTC($subframe, $this->getEndTimestamp() - self::MILLENNIUM_EPOCH);

    $subframe .= $this->getPayload();

    $frame = "";
    Buffer::packInt8u($frame, $this->getTazHeader());
    Buffer::packInt8u($frame, strlen($subframe));
    $frame .= $subframe;

    return $frame;
    }

  public function displayFrame()
    {
    return Buffer::displayOctetString($this->getFrame());
    }

  public function setTazHeader($taz_header)
    {
    $taz_header = intval($taz_header);

    if($taz_header < 0x00 || $taz_header > 0xff)
      throw new ZigbeeException("Invalid taz header");

    $this->setRequestTimestampPresent(($taz_header >> 0) & 0x01);
    $this->setExecuteTimestampPresent(($taz_header >> 1) & 0x01);
    $this->setEndTimestampPresent(($taz_header >> 2) & 0x01);
    $this->setApsFormat(($taz_header >> 3) & 0x03);
    $this->taz_header_reserved = ($taz_header >> 5) & 0x07;
    }

  public function getTazHeader()
    {
    return ($this->getRequestTimestampPresent() & 0x01) << 0 |
           ($this->getExecuteTimestampPresent() & 0x01) << 1 |
           ($this->getEndTimestampPresent() & 0x01) << 2 |
           ($this->getApsFormat() & 0x03) << 3 |
           ($this->taz_header_reserved & 0x07) << 5;
    }

  public function displayTazHeader()
    {
    return Buffer::displayBitmap8($this->getTazHeader());
    }

  public function getLength()
    {
    return strlen($this->getFrame());
    }

  public function setRequestTimestampPresent($request_timestamp_present)
    {
    $this->request_timestamp_present = $request_timestamp_present ? self::REQUEST_TIMESTAMP_IS_PRESENT : self::REQUEST_TIMESTAMP_NOT_PRESENT;
    }

  public function getRequestTimestampPresent()
    {
    return $this->request_timestamp_present;
    }

  public function displayRequestTimestampPresent()
    {
    $request_timestamp_present = $this->getRequestTimestampPresent();
    switch($request_timestamp_present)
      {
      case self::REQUEST_TIMESTAMP_NOT_PRESENT:
        $output = "Not Present";
        break;

      case self::REQUEST_TIMESTAMP_IS_PRESENT:
        $output = "Present";
        break;

      default:
        $output = "unknown";
        break;
      }

    return sprintf("%s (0x%02x)", $output, $request_timestamp_present);
    }

  public function setExecuteTimestampPresent($execute_timestamp_present)
    {
    $this->execute_timestamp_present = $execute_timestamp_present ? self::EXECUTE_TIMESTAMP_IS_PRESENT : self::EXECUTE_TIMESTAMP_NOT_PRESENT;
    }

  public function getExecuteTimestampPresent()
    {
    return $this->execute_timestamp_present;
    }

  public function displayExecuteTimestampPresent()
    {
    $execute_timestamp_present = $this->getExecuteTimestampPresent();
    switch($execute_timestamp_present)
      {
      case self::EXECUTE_TIMESTAMP_NOT_PRESENT:
        $output = "Not Present";
        break;

      case self::EXECUTE_TIMESTAMP_IS_PRESENT:
        $output = "Present";
        break;

      default:
        $output = "unknown";
        break;
      }

    return sprintf("%s (0x%02x)", $output, $execute_timestamp_present);
    }

  public function setEndTimestampPresent($end_timestamp_present)
    {
    $this->end_timestamp_present = $end_timestamp_present ? self::END_TIMESTAMP_IS_PRESENT : self::END_TIMESTAMP_NOT_PRESENT;
    }

  public function getEndTimestampPresent()
    {
    return $this->end_timestamp_present;
    }

  public function displayEndTimestampPresent()
    {
    $end_timestamp_present = $this->getEndTimestampPresent();
    switch ($end_timestamp_present)
      {
      case self::END_TIMESTAMP_NOT_PRESENT:
        $output = "Not Present";
        break;

      case self::END_TIMESTAMP_IS_PRESENT:
        $output = "Present";
        break;

      default:
        $output = "unknown";
        break;
      }

    return sprintf("%s (0x%02x)", $output, $end_timestamp_present);
    }

  public function setApsFormat($aps_format)
    {
    if(!in_array($aps_format, array(self::APS_FORMAT_SHORT_ZCL, self::APS_FORMAT_SHORT_ZDP, self::APS_FORMAT_NORMAL)))
      throw new ZigbeeException("Invalid aps format");

    $this->aps_format = $aps_format;
    }

  public function getApsFormat()
    {
    return $this->aps_format;
    }

  public function displayApsFormat()
    {
    $aps_format = $this->getApsFormat();
    switch ($aps_format)
      {
      case self::APS_FORMAT_NORMAL:
        $output = "Normal";
        break;

      case self::APS_FORMAT_SHORT_ZCL:
        $output = "Short ZCL";
        break;

      case self::APS_FORMAT_SHORT_ZDP:
        $output = "Short ZDP";
        break;

      case self::APS_FORMAT_RESERVED:
        $output = "Reserved";
        break;

      default:
        $output = "unknown";
        break;
      }

    return sprintf("%s (0x%02x)", $output, $aps_format);
    }

  public function setRequestTimestamp($request_timestamp)
    {
    if(!preg_match("/^[0-9]+$/", $request_timestamp))
      throw new ZigbeeException("Invalid timestamp");
    elseif($request_timestamp < self::MILLENNIUM_EPOCH)
      throw new ZigbeeException("Timestamp must be from Y2K");

    $this->request_timestamp = $request_timestamp;
    }

  public function getRequestTimestamp()
    {
    return $this->request_timestamp;
    }

  public function displayRequestTimestamp()
    {
    return date("d/m/Y H:i:s", $this->getRequestTimestamp());
    }

  public function setExecuteTimestamp($execute_timestamp)
    {
    if(!preg_match("/^[0-9]+$/", $execute_timestamp))
      throw new ZigbeeException("Invalid timestamp");
    elseif($execute_timestamp < self::MILLENNIUM_EPOCH)
      throw new ZigbeeException("Timestamp must be from Y2K");

    $this->execute_timestamp = $execute_timestamp;
    }

  public function getExecuteTimestamp()
    {
    return $this->execute_timestamp;
    }

  public function displayExecuteTimestamp()
    {
    return date("d/m/Y H:i:s", $this->getExecuteTimestamp());
    }

  public function setEndTimestamp($end_timestamp)
    {
    if(!preg_match("/^[0-9]+$/", $end_timestamp))
      throw new ZigbeeException("Invalid timestamp");
    elseif($end_timestamp < self::MILLENNIUM_EPOCH)
      throw new ZigbeeException("Timestamp must be from Y2K");

    $this->end_timestamp = $end_timestamp;
    }

  public function getEndTimestamp()
    {
    return $this->end_timestamp;
    }

  public function displayEndTimestamp()
    {
    return date("d/m/Y H:i:s", $this->getEndTimestamp());
    }

  public function setPayload($payload)
    {
    $this->payload = $payload;
    }

  public function getPayload()
    {
    return $this->payload;
    }

  public function setPayloadObject(APSFrame $object)
    {
    $this->setApsFormat($object->getFrameFormat());
    $this->setPayload($object->getFrame());
    }

  public function getPayloadObject()
    {
    return new APSFrame($this->getPayload(), $this->getApsFormat());
    }

  public function displayPayload()
    {
    return Buffer::displayOctetString($this->getPayload());
    }

  public function __toString()
    {
    try
      {
      $output = __CLASS__." (length: " . strlen($this->getFrame()) . ")" . PHP_EOL;
      $output .= "|- TazHeader        : " . $this->displayTazHeader() . PHP_EOL;
      $output .= "|  |- ReqTimePres   : " . $this->displayRequestTimestampPresent() . PHP_EOL;
      $output .= "|  |- ExecTimePre   : " . $this->displayExecuteTimestampPresent() . PHP_EOL;
      $output .= "|  |- EndTimePres   : " . $this->displayEndTimestampPresent() . PHP_EOL;
      $output .= "|  `- ApsFormat     : " . $this->displayApsFormat() . PHP_EOL;

      if($this->getRequestTimestampPresent())
        $output .= "|- RequestTimestamp : " . $this->displayRequestTimestamp() . PHP_EOL;

      if($this->getExecuteTimestampPresent())
        $output .= "|- ExecuteTimestamp : " . $this->displayExecuteTimestamp() . PHP_EOL;

      if($this->getEndTimestampPresent())
        $output .= "|- EndTimestamp     : " . $this->displayEndTimestamp() . PHP_EOL;

      $output .= "|- Payload (length: " . strlen($this->getPayload()) . ")" . PHP_EOL;
      try
        {
        $output .= preg_replace("/^   /", "`- ", preg_replace("/^/m", "   ", $this->getPayloadObject()));
        }
      catch(ZigbeeException $e)
        {
        $output .= "`-> " . $this->displayPayload() . PHP_EOL;
        }

      return $output;
      }
    catch(ZigbeeException $e)
      {
      return $e;
      }
    }
  }

