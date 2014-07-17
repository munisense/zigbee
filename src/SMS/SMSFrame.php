<?php

namespace Munisense\Zigbee\SMS;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\MuniZigbeeException;
use Munisense\Zigbee\IFrame;

class SMSFrame implements IFrame
  {
  const FRAME_ENCODING_BASE64 = 0x41;
  const FRAME_ENCODING_BINARY = 0x42;

  const SECURITY_DISABLED = 0x00;
  const SECURITY_ENABLED = 0x01;

  const ADDRESS_TYPE_NONE = 0x00;
  const ADDRESS_TYPE_EUI64 = 0x01;
  const ADDRESS_TYPE_NODE_ID = 0x02;
  const ADDRESS_TYPE_RESERVED = 0x03;

  const ACKNOWLEDGE_DISABLED = 0x00;
  const ACKNOWLEDGE_ENABLED = 0x01;

  const EXT_HEADER_NOT_PRESENT = 0x00;
  const EXT_HEADER_IS_PRESENT = 0x01;

  private $frame_encoding     = self::FRAME_ENCODING_BASE64;
  private $security           = self::SECURITY_DISABLED;
  private $address_type       = self::ADDRESS_TYPE_NONE;
  private $acknowledge        = self::ACKNOWLEDGE_DISABLED;
  private $ext_header_present = self::EXT_HEADER_NOT_PRESENT;

  private $ext_header         = 0x00;
  private $taz_block_count    = 0;
  private $address            = 0;

  /**
   * @var SMSTazBlock[]
   */
  private $taz_blocks = array();

  public function __construct($frame = null)
    {
    if($frame !== null)
      $this->setFrame($frame);
    }

  public function setFrame($frame)
    {
    $this->setFrameEncoding(Buffer::unpackInt8u($frame));

    if($this->getFrameEncoding() == self::FRAME_ENCODING_BASE64)
      $frame = base64_decode($frame);

    if($frame === false)
      throw new MuniZigbeeException("Error decoding base64");

    if(!self::validateChecksum($frame))
      throw new MuniZigbeeException("Invalid checksum");

    $this->setSMSHeader(Buffer::unpackInt8u($frame));

    Buffer::unpackInt16u($frame); // Checksum

    if($this->isExtHeaderPresent())
      $this->setExtHeader(Buffer::unpackInt8u($frame));

    if($this->isTazBlockCountPresent())
      $this->setTazBlockCount(Buffer::unpackInt8u($frame));

    if($this->isAddressPresent())
      {
      switch($this->getAddressType())
        {
        case self::ADDRESS_TYPE_EUI64: $this->setAddress(Buffer::unpackEui64($frame)); break;
        case self::ADDRESS_TYPE_NODE_ID: $this->setAddress(Buffer::unpackInt16u($frame)); break;
        }
      }

    for($taz_index = 0; $taz_index < $this->getTazBlockCount(); $taz_index++)
      {
      $header = Buffer::unpackInt8u($frame);
      $length = Buffer::unpackInt8u($frame);

      $taz_frame = "";
      Buffer::packInt8u($taz_frame, $header);
      Buffer::packInt8u($taz_frame, $length);

      if($length > strlen($frame))
        throw new MuniZigbeeException("Taz frame is too short");

      $taz_frame .= substr($frame, 0, $length);
      $frame = substr($frame, $length);

      $this->taz_blocks[$taz_index] = new SMSTazBlock($taz_frame);
      }

    if(strlen($frame) > 0)
      throw new MuniZigbeeException("Unparsed data (".strlen($frame)." bytes) at end of frame");
    }

  public function getFrame()
    {
    $checksum_frame = "";

    Buffer::packInt8u($checksum_frame, $this->getSMSHeader());
    Buffer::packInt16u($checksum_frame, 0x0000);

    if($this->isExtHeaderPresent())
      Buffer::packInt8u($checksum_frame, $this->getExtHeader());

    if($this->isTazBlockCountPresent())
      Buffer::packInt8u($checksum_frame, $this->getTazBlockCount());

    switch($this->getAddressType())
      {
      case self::ADDRESS_TYPE_EUI64: Buffer::packEui64($checksum_frame, $this->getAddress()); break;
      case self::ADDRESS_TYPE_NODE_ID: Buffer::packInt16u($checksum_frame, $this->getAddress()); break;
      }

    for($taz_index = 0; $taz_index < $this->getTazBlockCount(); $taz_index++)
      $checksum_frame .= $this->taz_blocks[$taz_index]->getFrame();

    $checksum_frame = self::applyChecksum($checksum_frame);

    if($this->getFrameEncoding() == self::FRAME_ENCODING_BASE64)
      $checksum_frame = base64_encode($checksum_frame);

    $frame = "";
    Buffer::packInt8u($frame, $this->getFrameEncoding());
    $frame .= $checksum_frame;

//echo __CLASS__."->".__FUNCTION__."(): ".Buffer::displayOctetString($frame).PHP_EOL;

    return $frame;
    }

  public function displayFrame()
    {
    return Buffer::displayOctetString($this->getFrame());
    }

  public function setFrameEncoding($frame_encoding)
    {
    if(!in_array($frame_encoding, array(self::FRAME_ENCODING_BINARY, self::FRAME_ENCODING_BASE64)))
      throw new MuniZigbeeException("Invalid frame encoding");

    $this->frame_encoding = $frame_encoding;
    }

  public function getFrameEncoding()
    {
    return $this->frame_encoding;
    }

  public function displayFrameEncoding()
    {
    $frame_encoding = $this->getFrameEncoding();
    switch($frame_encoding)
      {
      case self::FRAME_ENCODING_BASE64: $output = "Base64"; break;
      case self::FRAME_ENCODING_BINARY: $output = "Binary"; break;
      default: $output = "unknown"; break;
      }

    return sprintf("%s (0x%02x)", $output, $frame_encoding);
    }

  public function setSMSHeader($sms_header)
    {
    $sms_header = intval($sms_header);

    if($sms_header < 0x00 || $sms_header > 0xff)
      throw new MuniZigbeeException("Invalid frame control");

    $this->setSecurity(($sms_header >> 0) & 0x01);
    $this->setAddressType(($sms_header >> 1) & 0x03);
    $this->setAcknowledge(($sms_header >> 3) & 0x01);
    $this->setTazBlockCount(($sms_header >> 4) & 0x07);
    $this->setExtHeaderPresent(($sms_header >> 7) & 0x01);
    }

  public function getSMSHeader()
    {
    if($this->isTazBlockCountPresent())
      $taz_block_count = 0x00;
    else
      $taz_block_count = $this->getTazBlockCount();

    return ($this->getSecurity()         & 0x01) << 0 |
           ($this->getAddressType( )     & 0x03) << 1 |
           ($this->getAcknowledge()      & 0x01) << 3 |
           ($taz_block_count             & 0x07) << 4 |
           ($this->getExtHeaderPresent() & 0x01) << 7;
    }

  public function displaySMSHeader()
    {
    return Buffer::displayBitmap8($this->getSMSHeader());
    }

  public function setSecurity($security)
    {
    $this->security = $security ? self::SECURITY_ENABLED : self::SECURITY_DISABLED;
    }

  public function getSecurity()
    {
    return $this->security;
    }

  public function displaySecurity()
    {
    $security = $this->getSecurity();
    switch($security)
      {
      case self::SECURITY_DISABLED: $output = "Disabled"; break;
      case self::SECURITY_ENABLED: $output = "Enabled"; break;
      default: $output = "unknown"; break;
      }

    return sprintf("%s (0x%02x)", $output, $security);
    }

  public function setAddressType($address_type)
    {
    $address_type = intval($address_type);

    if($address_type < 0x00 || $address_type > 0x02)
      throw new MuniZigbeeException("Invalid frame type");

    $this->address_type = $address_type;
    }

  public function getAddressType()
    {
    return $this->address_type;
    }

  public function displayAddressType()
    {
    $address_type = $this->getAddressType();
    switch($address_type)
      {
      case self::ADDRESS_TYPE_NONE: $output = "None"; break;
      case self::ADDRESS_TYPE_EUI64: $output = "Eui64"; break;
      case self::ADDRESS_TYPE_NODE_ID: $output = "NodeId"; break;
      case self::ADDRESS_TYPE_RESERVED: $output = "Reserved"; break;
      default: $output = "unknown"; break;
      }

    return sprintf("%s (0x%02x)", $output, $address_type);
    }

  public function setAcknowledge($acknowledge)
    {
    $this->acknowledge = $acknowledge ? self::ACKNOWLEDGE_ENABLED : self::ACKNOWLEDGE_DISABLED;
    }

  public function getAcknowledge()
    {
    return $this->acknowledge;
    }

  public function displayAcknowledge()
    {
    $acknowledge = $this->getAcknowledge();
    switch($acknowledge)
      {
      case self::ACKNOWLEDGE_DISABLED: $output = "Disabled"; break;
      case self::ACKNOWLEDGE_ENABLED: $output = "Enabled"; break;
      default: $output = "unknown"; break;
      }

    return sprintf("%s (0x%02x)", $output, $acknowledge);
    }

  public function setExtHeaderPresent($ext_header_present)
    {
    $this->ext_header_present = $ext_header_present ? self::EXT_HEADER_IS_PRESENT : self::EXT_HEADER_NOT_PRESENT;
    }

  public function getExtHeaderPresent()
    {
    return $this->ext_header_present;
    }

  public function displayExtHeaderPresent()
    {
    $ext_header_present = $this->getExtHeaderPresent();
    switch($ext_header_present)
      {
      case self::EXT_HEADER_NOT_PRESENT: $output = "Not Present"; break;
      case self::EXT_HEADER_IS_PRESENT: $output = "Present"; break;
      default: $output = "unknown"; break;
      }

    return sprintf("%s (0x%02x)", $output, $ext_header_present);
    }

  public function getChecksum()
    {
    $frame = $this->getFrame();
    Buffer::unpackInt8u($frame);
    return self::generateChecksum($frame);
    }

  public function displayChecksum()
    {
    return sprintf("0x%04x", $this->getChecksum());
    }

  public function setExtHeader($ext_header)
    {
    $ext_header = intval($ext_header);

    if($ext_header < 0x00 || $ext_header > 0xff)
      throw new MuniZigbeeException("Invalid frame control");

    $this->ext_header = $ext_header;
    }

  public function getExtHeader()
    {
    return $this->ext_header;
    }

  public function displayExtHeader()
    {
    return Buffer::displayBitmap8($this->getExtHeader());
    }

  public function setTazBlockCount($taz_block_count)
    {
    $taz_block_count = intval($taz_block_count);
    if($taz_block_count < 0x00 || $taz_block_count > 0xff)
      throw new MuniZigbeeException("Invalid destination endpoint");

    $this->taz_block_count = $taz_block_count;
    }

  public function getTazBlockCount()
    {
    return $this->taz_block_count;
    }

  public function displayTazBlockCount()
    {
    return sprintf("0x%02x", $this->getTazBlockCount());
    }

  public function setAddress($address)
    {
    $this->address = $address;
    }

  public function getAddress()
    {
    return $this->address;
    }

  public function displayAddress()
    {
    switch($this->getAddressType())
      {
      case self::ADDRESS_TYPE_EUI64: return Buffer::displayEui64($this->getAddress()); break;
      case self::ADDRESS_TYPE_NODE_ID: return sprintf("0x%04x", $this->getAddress()); break;
      }

    return "-";
    }

  public function setTazBlocks(array $taz_blocks)
    {
    foreach($taz_blocks as $taz_block)
      if(!$taz_block instanceof SMSTazBlock)
        throw new MuniZigbeeException("One of the TazBlocks is not a SMSTazBlock");

    $this->taz_blocks = $taz_blocks;
    $this->setTazBlockCount(count($this->getTazBlocks()));
    }

  public function getTazBlocks()
    {
    return $this->taz_blocks;
    }

  public function addTazBlock(SMSTazBlock $taz_block)
    {
    $this->taz_blocks[] = $taz_block;
    $this->setTazBlockCount(count($this->getTazBlocks()));
    }

  static public function generateChecksum($frame)
    {
    $frame[1] = chr(0);
    $frame[2] = chr(0);
    return self::generateCrc16($frame);
    }

  static public function applyChecksum($frame)
    {
    $checksum = self::generateChecksum($frame);
    $frame[1] = chr(($checksum >> 0) & 0xff);
    $frame[2] = chr(($checksum >> 8) & 0xff);

    return $frame;
    }

  static public function validateChecksum($frame)
    {
    $checksum_frame = self::generateChecksum($frame);

    Buffer::unpackInt8u($frame);
    $checksum_field = Buffer::unpackInt16u($frame);

    if($checksum_field === $checksum_frame)
      return true;

    return false;
    }

  static function generateCrc16($frame)
    {
    $crc_table = array(0x0000,  0x1021,  0x2042,  0x3063,  0x4084,  0x50a5,  0x60c6,  0x70e7,
                       0x8108,  0x9129,  0xa14a,  0xb16b,  0xc18c,  0xd1ad,  0xe1ce,  0xf1ef,
                       0x1231,  0x0210,  0x3273,  0x2252,  0x52b5,  0x4294,  0x72f7,  0x62d6,
                       0x9339,  0x8318,  0xb37b,  0xa35a,  0xd3bd,  0xc39c,  0xf3ff,  0xe3de,
                       0x2462,  0x3443,  0x0420,  0x1401,  0x64e6,  0x74c7,  0x44a4,  0x5485,
                       0xa56a,  0xb54b,  0x8528,  0x9509,  0xe5ee,  0xf5cf,  0xc5ac,  0xd58d,
                       0x3653,  0x2672,  0x1611,  0x0630,  0x76d7,  0x66f6,  0x5695,  0x46b4,
                       0xb75b,  0xa77a,  0x9719,  0x8738,  0xf7df,  0xe7fe,  0xd79d,  0xc7bc,
                       0x48c4,  0x58e5,  0x6886,  0x78a7,  0x0840,  0x1861,  0x2802,  0x3823,
                       0xc9cc,  0xd9ed,  0xe98e,  0xf9af,  0x8948,  0x9969,  0xa90a,  0xb92b,
                       0x5af5,  0x4ad4,  0x7ab7,  0x6a96,  0x1a71,  0x0a50,  0x3a33,  0x2a12,
                       0xdbfd,  0xcbdc,  0xfbbf,  0xeb9e,  0x9b79,  0x8b58,  0xbb3b,  0xab1a,
                       0x6ca6,  0x7c87,  0x4ce4,  0x5cc5,  0x2c22,  0x3c03,  0x0c60,  0x1c41,
                       0xedae,  0xfd8f,  0xcdec,  0xddcd,  0xad2a,  0xbd0b,  0x8d68,  0x9d49,
                       0x7e97,  0x6eb6,  0x5ed5,  0x4ef4,  0x3e13,  0x2e32,  0x1e51,  0x0e70,
                       0xff9f,  0xefbe,  0xdfdd,  0xcffc,  0xbf1b,  0xaf3a,  0x9f59,  0x8f78,
                       0x9188,  0x81a9,  0xb1ca,  0xa1eb,  0xd10c,  0xc12d,  0xf14e,  0xe16f,
                       0x1080,  0x00a1,  0x30c2,  0x20e3,  0x5004,  0x4025,  0x7046,  0x6067,
                       0x83b9,  0x9398,  0xa3fb,  0xb3da,  0xc33d,  0xd31c,  0xe37f,  0xf35e,
                       0x02b1,  0x1290,  0x22f3,  0x32d2,  0x4235,  0x5214,  0x6277,  0x7256,
                       0xb5ea,  0xa5cb,  0x95a8,  0x8589,  0xf56e,  0xe54f,  0xd52c,  0xc50d,
                       0x34e2,  0x24c3,  0x14a0,  0x0481,  0x7466,  0x6447,  0x5424,  0x4405,
                       0xa7db,  0xb7fa,  0x8799,  0x97b8,  0xe75f,  0xf77e,  0xc71d,  0xd73c,
                       0x26d3,  0x36f2,  0x0691,  0x16b0,  0x6657,  0x7676,  0x4615,  0x5634,
                       0xd94c,  0xc96d,  0xf90e,  0xe92f,  0x99c8,  0x89e9,  0xb98a,  0xa9ab,
                       0x5844,  0x4865,  0x7806,  0x6827,  0x18c0,  0x08e1,  0x3882,  0x28a3,
                       0xcb7d,  0xdb5c,  0xeb3f,  0xfb1e,  0x8bf9,  0x9bd8,  0xabbb,  0xbb9a,
                       0x4a75,  0x5a54,  0x6a37,  0x7a16,  0x0af1,  0x1ad0,  0x2ab3,  0x3a92,
                       0xfd2e,  0xed0f,  0xdd6c,  0xcd4d,  0xbdaa,  0xad8b,  0x9de8,  0x8dc9,
                       0x7c26,  0x6c07,  0x5c64,  0x4c45,  0x3ca2,  0x2c83,  0x1ce0,  0x0cc1,
                       0xef1f,  0xff3e,  0xcf5d,  0xdf7c,  0xaf9b,  0xbfba,  0x8fd9,  0x9ff8,
                       0x6e17,  0x7e36,  0x4e55,  0x5e74,  0x2e93,  0x3eb2,  0x0ed1,  0x1ef0);

    $crc = 0xffff;
    $size = strlen($frame);
    for($i = 0; $i < $size; $i++)
      $crc = ($crc_table[(($crc >> 8) & 255)] ^ ($crc << 8) ^ ord(substr($frame, $i, 1))) & 0xffff;

    return $crc;
    }

  private function isExtHeaderPresent()
    {
    return $this->getExtHeaderPresent();
    }

  private function isTazBlockCountPresent()
    {
    if($this->getTazBlockCount() > 0x07)
      return true;

    return false;
    }

  private function isAddressPresent()
    {
    if(in_array($this->getAddressType(), array(self::ADDRESS_TYPE_EUI64, self::ADDRESS_TYPE_NODE_ID)))
      return true;

    return false;
    }

  public function __toString()
    {
    $output =  __CLASS__." (length: ".strlen($this->getFrame()).")".PHP_EOL;
    $output .= "|- FrameEncoding    : ".$this->displayFrameEncoding().PHP_EOL;
    $output .= "|- SMSHeader        : ".$this->displaySMSHeader().PHP_EOL;
    $output .= "|  |- Security      : ".$this->displaySecurity().PHP_EOL;
    $output .= "|  |- AddressType   : ".$this->displayAddressType().PHP_EOL;
    $output .= "|  |- Acknowledge   : ".$this->displayAcknowledge().PHP_EOL;

    if($this->isTazBlockCountPresent())
      $output .= "|  |- TazBlockCount : extended field".PHP_EOL;
    else
      $output .= "|  |- TazBlockCount : ".$this->displayTazBlockCount().PHP_EOL;

    $output .= "|  `- ExtHeaderPres : ".$this->displayExtHeaderPresent().PHP_EOL;
    $output .= "|- Checksum         : ".$this->displayChecksum().PHP_EOL;

    if($this->isExtHeaderPresent())
      $output .= "|- extHeader        : ".$this->displayExtHeader().PHP_EOL;


    if($this->isTazBlockCountPresent())
      $output .= "|- TazBlockCount    : ".$this->displayTazBlockCount().PHP_EOL;

    if($this->isAddressPresent())
      $output .= "|- Address          : ".$this->displayAddress().PHP_EOL;

    for($taz_index = 0; $taz_index < $this->getTazBlockCount(); $taz_index++)
      {
      $output .= preg_replace("/^   /", "`- ", preg_replace("/^/m", "   ", $this->taz_blocks[$taz_index]));
      if($taz_index < $this->getTazBlockCount() - 1)
        $output = preg_replace("/^[\s`]/m", "|", $output);
      }

    return $output;
    }
  }


