<?php

namespace Munisense\Zigbee\APS;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\MuniZigbeeException;
use Munisense\Zigbee\IFrame;
use Munisense\Zigbee\ZCL\ZCLFrame;
use Munisense\Zigbee\ZDO\ZDOFrame;

/**
 * Zigbee Specification: 2.2.5.1
 */
class APSFrame implements IFrame
  {
  // Munisense Specific frame format
  const FRAME_FORMAT_NORMAL = 0x00;
  const FRAME_FORMAT_SHORT_ZCL = 0x01;
  const FRAME_FORMAT_SHORT_ZDO = 0x02;
  const FRAME_FORMAT_RESERVED = 0x03;
  private $frame_format = self::FRAME_FORMAT_NORMAL;

  // Zigbee Specification: 2.2.5.1.1
  const FRAME_TYPE_DATA = 0x00;
  const FRAME_TYPE_COMMAND = 0x01;
  const FRAME_TYPE_ACKNOWLEDGE = 0x02;
  private $frame_type = self::FRAME_TYPE_DATA;

  // Zigbee Specification: 2.2.5.1.2
  const DELIVERY_MODE_UNICAST = 0x00;
  const DELIVERY_MODE_INDIRECT = 0x01;
  const DELIVERY_MODE_BROADCAST = 0x02;
  const DELIVERY_MODE_GROUP_ADDRESS = 0x03;
  private $delivery_mode = self::DELIVERY_MODE_UNICAST;

  // Zigbee Specification: 2.2.5.1.3
  const ACK_FORMAT_DATA = 0x00;
  const ACK_FORMAT_COMMAND = 0x01;
  private $ack_format = self::ACK_FORMAT_DATA;

  // Zigbee Specification: 2.2.5.1.4
  const SECURITY_DISABLED = 0x00;
  const SECURITY_ENABLED = 0x01;
  private $security = self::SECURITY_DISABLED;

  // Zigbee Specification: 2.2.5.1.5
  const ACK_REQUEST_DISABLED = 0x00;
  const ACK_REQUEST_ENABLED = 0x01;
  private $ack_request = self::ACK_REQUEST_DISABLED;

  // Zigbee Specification: 2.2.5.1.6
  const EXT_HEADER_NOT_PRESENT = 0x00;
  const EXT_HEADER_IS_PRESENT = 0x01;
  private $ext_header_present = self::EXT_HEADER_NOT_PRESENT;

  // Zigbee Specification: 2.2.5.8.1
  const FRAGMENTATION_DISABLED = 0x00;
  const FRAGMENTATION_FIRST = 0x01;
  const FRAGMENTATION_FOLLOWING = 0x02;
  private $fragmentation = self::FRAGMENTATION_DISABLED;

  // Zigbee Specification: 2.2.5.1.8.2
  private $frag_block_number = 0;

  // Zigbee Specification: 2.2.5.1.8.3
  private $frag_ack_bitfield = 0b00000000;

  // Zigbee Specification: 2.2.5.1.2
  private $destination_endpoint = 0x00;

  // Zigbee Specification: 2.2.5.1.3
  private $group_address = 0x0000;

  // Zigbee Specification: 2.2.5.1.4
  private $cluster_id = 0x0000;

  // Zigbee Specification: 2.2.5.1.5
  private $profile_id = 0x0000;

  // Zigbee Specification: 2.2.5.1.6
  private $source_endpoint = 0x00;

  // Zigbee Specification: 2.2.5.1.7
  private $aps_counter = 0x00;

  // Zigbee Specification: 2.2.5.1.9
  private $payload = "";


  /**
   * Create a new Zigbee APS Frame
   *
   * When the frame argument you will parse the frame and set all
   * parameters acordingly. When no frame is given the APS frame is
   * filled with default parameters.
   *
   * The frame format is not packaged in the frame so it needs to
   * be set out-of-band to the object. This can be done here with
   * an agument to the constructor or by setting the frame_format field.
   *
   * @param string $frame Byte string of the APS frame
   * @param int $frame_format (enum) Format to parse the frame in (default: FRAME_FORMAT_NORMAL, options: FRAME_FORMAT_*)
   * @return APSFrame
   */
  public function __construct($frame = null, $frame_format = null)
    {
    if($frame !== null)
      $this->setFrame($frame, $frame_format);
    }

  /**
   * Set all parameters of this object by parsing a frame
   *
   * The frame format will determine the way the APS frame is
   * constructed. Two basic formats are available: 'normal', 
   * according to Zigbee specs and 'short', a munisense 
   * propriatary format to minimize data.
   *
   * The 'short' frame format is commonly used in the
   * SMS Frames.
   *
   * @param string $frame Byte string of the APS frame
   * @param int $frame_format (enum) format to parse the frame in (default: FRAME_FORMAT_NORMAL, options: FRAME_FORMAT_*)
   * @return void
   */
  public function setFrame($frame, $frame_format = null)
    {
    if($frame_format !== null)
      $this->setFrameFormat($frame_format);

    if($this->isShortFrame())
      $this->setShortFrame($frame);
    else
      $this->setNormalFrame($frame);
    }

  /**
   * Set all parameters of this object by parsing a frame
   * using the normal APS format.
   *
   * @param string $frame Byte string of the APS frame
   * @throws MuniZigbeeException
   * @return void
   */
  private function setNormalFrame($frame)
    {
    $this->setFrameControl(Buffer::unpackInt8u($frame));

    if($this->isGroupAddressPresent())
      $this->setGroupAddress(Buffer::unpackInt16u($frame));

    if($this->isDestinationEndpointPresent())
      $this->setDestinationEndpoint(Buffer::unpackInt8u($frame));

    if($this->isClusterIdPresent())
      $this->setClusterId(Buffer::unpackInt16u($frame));

    if($this->isProfileIdPresent())
      $this->setProfileId(Buffer::unpackInt16u($frame));

    if($this->isSourceEndpointPresent())
      $this->setSourceEndpoint(Buffer::unpackInt8u($frame));

    $this->setApsCounter(Buffer::unpackInt8u($frame));

    if($this->isExtHeaderPresent())
      $this->setExtHeader(Buffer::unpackInt8u($frame));

    if($this->isFragBlockNumberPresent())
      $this->setFragBlockNumber(Buffer::unpackInt8u($frame));

    if($this->isFragAckBitfieldPresent())
      $this->setFragAckBitfield(Buffer::unpackInt8u($frame));

    if($this->isPayloadPresent())
      $this->setPayload($frame);
    elseif(strlen($frame) > 0)
      throw new MuniZigbeeException("Unparsed data (".strlen($frame)." bytes) at end of frame");
    }

  /**
   * Set all parameters of this object by parsing a frame
   * using the Munisense 'short' APS format.
   *
   * @param string $frame Byte string of the APS frame
   * @throws MuniZigbeeException
   * @return void
   */
  private function setShortFrame($frame)
    {
    $this->setFrameControl(Buffer::unpackInt8u($frame));

    if($this->isClusterIdPresent())
      $this->setClusterId(Buffer::unpackInt16u($frame));

    if($this->isExtHeaderPresent())
      $this->setExtHeader(Buffer::unpackInt8u($frame));

    if($this->isFragBlockNumberPresent())
      $this->setFragBlockNumber(Buffer::unpackInt8u($frame));

    if($this->isFragAckBitfieldPresent())
      $this->setFragAckBitfield(Buffer::unpackInt8u($frame));

    if($this->isPayloadPresent())
      $this->setPayload($frame);
    elseif(strlen($frame) > 0)
      throw new MuniZigbeeException("Unparsed data (".strlen($frame)." bytes) at end of frame");
    }

  /**
   * Get the current frame formed to the selected frame format.
   *
   * @return string Frame
   */
  public function getFrame()
    {
    if($this->isShortFrame())
      return $this->getShortFrame();

    return $this->getNormalFrame();
    }

  /**
   * Get the current frame formed in normal APS format
   *
   * @return string Frame
   */
  private function getNormalFrame()
    {
    $frame = "";

    Buffer::packInt8u($frame, $this->getFrameControl());

    if($this->isGroupAddressPresent())
      Buffer::packInt16u($frame, $this->getGroupAddress());

    if($this->isDestinationEndpointPresent())
      Buffer::packInt8u($frame, $this->getDestinationEndpoint());

    if($this->isClusterIdPresent())
      Buffer::packInt16u($frame, $this->getClusterId());

    if($this->isProfileIdPresent())
      Buffer::packInt16u($frame, $this->getProfileId());

    if($this->isSourceEndpointPresent())
      Buffer::packInt8u($frame, $this->getSourceEndpoint());

    Buffer::packInt8u($frame, $this->getApsCounter());

    if($this->isExtHeaderPresent())
      Buffer::packInt8u($frame, $this->getExtHeader());

    if($this->isFragBlockNumberPresent())
      Buffer::packInt8u($frame, $this->getFragBlockNumber());

    if($this->isFragAckBitfieldPresent())
      Buffer::packInt8u($frame, $this->getFragAckBitfield());

    if($this->isPayloadPresent())
      $frame .= $this->getPayload();

    return $frame;
    }

  /**
   * Get the current frame formed in the Munisense
   * 'short' frame format.
   *
   * @return string Frame
   */
  private function getShortFrame()
    {
    $frame = "";

    Buffer::packInt8u($frame, $this->getFrameControl());

    if($this->isClusterIdPresent())
      Buffer::packInt16u($frame, $this->getClusterId());

    if($this->isExtHeaderPresent())
      Buffer::packInt8u($frame, $this->getExtHeader());

    if($this->isFragBlockNumberPresent())
      Buffer::packInt8u($frame, $this->getFragBlockNumber());

    if($this->isFragAckBitfieldPresent())
      Buffer::packInt8u($frame, $this->getFragAckBitfield());

    if($this->isPayloadPresent())
      $frame .= $this->getPayload();

    return $frame;
    }

  /**
   * Determine if the frame format is short.
   *
   * @return bool True for short frame, false for normal frame
   */
  public function isShortFrame()
    {
    if(in_array($this->getFrameFormat(), array(self::FRAME_FORMAT_SHORT_ZCL, self::FRAME_FORMAT_SHORT_ZDO)))
      return true;

    return false;
    }

  /**
   * Returns a displayable version of the frame
   *
   * @return string Displayable version of the frame
   */

  public function displayFrame()
    {
    return Buffer::displayOctetString($this->getFrame());
    }

  /**
   * Set the frame format using one of the FRAME_FORMAT_* constants.
   *
   * @param int $frame_format Enumeration of the frame format
   * @throws MuniZigbeeException if the frame format was invalid
   * @return void
   */
  public function setFrameFormat($frame_format)
    {
    $frame_format = intval($frame_format);

    if($frame_format < 0x00 || $frame_format > 0x02)
      throw new MuniZigbeeException("Invalid frame format");

    $this->frame_format = $frame_format;
    }

  /**
   * Get the frame format in form of a FRAME_FORMAT_* constant.
   *
   * @return int Enumeration of the frame format
   */
  public function getFrameFormat()
    {
    return $this->frame_format;
    }

  public function displayFrameFormat()
    {
    $frame_format = $this->getFrameFormat();
    switch($frame_format)
      {
      case self::FRAME_FORMAT_NORMAL: $output = "Normal"; break;
      case self::FRAME_FORMAT_SHORT_ZCL: $output = "Short ZCL"; break;
      case self::FRAME_FORMAT_SHORT_ZDO: $output = "Short ZDO"; break;
      case self::FRAME_FORMAT_RESERVED: $output = "Reserved"; break;
      default: $output = "unknown"; break;
      }

    return sprintf("%s (0x%02x)", $output, $frame_format);
    }

  public function setFrameControl($frame_control)
    {
    $frame_control = intval($frame_control);

    if($frame_control < 0x00 || $frame_control > 0xff)
      throw new MuniZigbeeException("Invalid frame control");

    $this->setFrameType(($frame_control >> 0) & 0x03);
    $this->setDeliveryMode(($frame_control >> 2) & 0x03);
    $this->setAckFormat(($frame_control >> 4) & 0x01);
    $this->setSecurity(($frame_control >> 5) & 0x01);
    $this->setAckRequest(($frame_control >> 6) & 0x01);
    $this->setExtHeaderPresent(($frame_control >> 7) & 0x01);
    }

  public function getFrameControl()
    {
    return ($this->getFrameType()        & 0x03) << 0 |
           ($this->getDeliveryMode()     & 0x03) << 2 |
           ($this->getAckFormat()        & 0x01) << 4 |
           ($this->getSecurity()         & 0x01) << 5 |
           ($this->getAckRequest()       & 0x01) << 6 |
           ($this->getExtHeaderPresent() & 0x01) << 7;
    }

  public function displayFrameControl()
    {
    return Buffer::displayBitmap8($this->getFrameControl());
    }

  public function setFrameType($frame_type)
    {
    $frame_type = intval($frame_type);

    if($frame_type < 0x00 || $frame_type > 0x02)
      throw new MuniZigbeeException("Invalid frame type");

    $this->frame_type = $frame_type;
    }

  public function getFrameType()
    {
    return $this->frame_type;
    }

  public function displayFrameType()
    {
    $frame_type = $this->getFrameType();
    switch($frame_type)
      {
      case self::FRAME_TYPE_DATA: $output = "Data"; break;
      case self::FRAME_TYPE_COMMAND: $output = "Command"; break;
      case self::FRAME_TYPE_ACKNOWLEDGE: $output = "Acknowledge"; break;
      default: $output = "unknown"; break;
      }

    return sprintf("%s (0x%02x)", $output, $frame_type);
    }

  public function setDeliveryMode($delivery_mode)
    {
    $delivery_mode = intval($delivery_mode);

    if($delivery_mode < 0x00 || $delivery_mode > 0x03)
      throw new MuniZigbeeException("Invalid delivery mode");

    $this->delivery_mode = $delivery_mode;
    }

  public function getDeliveryMode()
    {
    return $this->delivery_mode;
    }

  public function displayDeliveryMode()
    {
    $delivery_mode = $this->getDeliveryMode();
    switch($delivery_mode)
      {
      case self::DELIVERY_MODE_UNICAST: $output = "Unicast"; break;
      case self::DELIVERY_MODE_INDIRECT: $output = "Indirect"; break;
      case self::DELIVERY_MODE_BROADCAST: $output = "Broadcast"; break;
      case self::DELIVERY_MODE_GROUP_ADDRESS: $output = "Group Address"; break;
      default: $output = "unknown"; break;
      }

    return sprintf("%s (0x%02x)", $output, $delivery_mode);
    }

  public function setAckFormat($ack_format)
    {
    $this->ack_format = $ack_format ? self::ACK_FORMAT_COMMAND : self::ACK_FORMAT_DATA;
    }

  public function getAckFormat()
    {
    return $this->ack_format;
    }

  public function displayAckFormat()
    {
    $ack_format = $this->getAckFormat();
    switch($ack_format)
      {
      case self::ACK_FORMAT_DATA: $output = "Data"; break;
      case self::ACK_FORMAT_COMMAND: $output = "Command"; break;
      default: $output = "unknown"; break;
      }

    return sprintf("%s (0x%02x)", $output, $ack_format);
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

  public function setAckRequest($ack_request)
    {
    $this->ack_request = $ack_request ? self::ACK_REQUEST_ENABLED : self::ACK_REQUEST_DISABLED;
    }

  public function getAckRequest()
    {
    return $this->ack_request;
    }

  public function displayAckRequest()
    {
    $ack_request = $this->getAckRequest();
    switch($ack_request)
      {
      case self::ACK_REQUEST_DISABLED: $output = "Disabled"; break;
      case self::ACK_REQUEST_ENABLED: $output = "Enabled"; break;
      default: $output = "unknown"; break;
      }

    return sprintf("%s (0x%02x)", $output, $ack_request);
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

  public function setDestinationEndpoint($destination_endpoint)
    {
    $destination_endpoint = intval($destination_endpoint);
    if($destination_endpoint < 0x00 || $destination_endpoint > 0xff)
      throw new MuniZigbeeException("Invalid destination endpoint");

    $this->destination_endpoint = $destination_endpoint;
    }

  public function getDestinationEndpoint()
    {
    return $this->destination_endpoint;
    }

  public function displayDestinationEndpoint()
    {
    return sprintf("0x%02x", $this->getDestinationEndpoint());
    }

  public function setGroupAddress($group_address)
    {
    $group_address = intval($group_address);
    if($group_address < 0x00 || $group_address > 0xffff)
      throw new MuniZigbeeException("Invalid group address");

    $this->group_address = $group_address;
    }

  public function getGroupAddress()
    {
    return $this->group_address;
    }

  public function displayGroupAddress()
    {
    return sprintf("0x%04x", $this->getGroupAddress());
    }

  public function setClusterId($cluster_id)
    {
    $cluster_id = intval($cluster_id);
    if($cluster_id < 0x00 || $cluster_id > 0xffff)
      throw new MuniZigbeeException("Invalid cluster id");

    $this->cluster_id = $cluster_id;
    }

  public function getClusterId()
    {
    return $this->cluster_id;
    }

  public function displayClusterId()
    {
    return sprintf("0x%04x", $this->getClusterId());
    }

  public function setProfileId($profile_id)
    {
    $profile_id = intval($profile_id);
    if($profile_id < 0x00 || $profile_id > 0xffff)
      throw new MuniZigbeeException("Invalid profile id");

    $this->profile_id = $profile_id;
    }

  public function getProfileId()
    {
    return $this->profile_id;
    }

  public function displayProfileId()
    {
    return sprintf("0x%04x", $this->getProfileId());
    }

  public function setSourceEndpoint($source_endpoint)
    {
    $source_endpoint = intval($source_endpoint);
    if($source_endpoint < 0x00 || $source_endpoint > 0xff)
      throw new MuniZigbeeException("Invalid source endpoint");

    $this->source_endpoint = $source_endpoint;
    }

  public function getSourceEndpoint()
    {
    return $this->source_endpoint;
    }

  public function displaySourceEndpoint()
    {
    return sprintf("0x%02x", $this->getSourceEndpoint());
    }

  public function setApsCounter($aps_counter)
    {
    $aps_counter = intval($aps_counter);
    if($aps_counter < 0x00 || $aps_counter > 0xff)
      throw new MuniZigbeeException("Invalid destination endpoint");

    $this->aps_counter = $aps_counter;
    }

  public function getApsCounter()
    {
    return $this->aps_counter;
    }

  public function displayApsCounter()
    {
    return sprintf("0x%02x", $this->getApsCounter());
    }

  public function setExtHeader($ext_header)
    {
    $ext_header = intval($ext_header);
    if($ext_header < 0x00 || $ext_header > 0xff)
      throw new MuniZigbeeException("Invalid frame control");

    $this->setFragmentation(($ext_header >> 0) & 0x03);
    }

  public function getExtHeader()
    {
    return ($this->getFragmentation() & 0x03) << 0;
    }

  public function displayExtHeader()
    {
    return Buffer::displayBitmap8($this->getExtHeader());
    }

  public function setFragmentation($fragmentation)
    {
    $fragmentation = intval($fragmentation);
    if($fragmentation < 0x00 || $fragmentation > 0x02)
      throw new MuniZigbeeException("Invalid fragmentation field");

    $this->fragmentation = $fragmentation;
    }

  public function getFragmentation()
    {
    return $this->fragmentation;
    }

  public function displayFragmentation()
    {
    $fragmentation = $this->getFragmentation();
    switch($fragmentation)
      {
      case self::FRAGMENTATION_DISABLED: $output = "Disabled"; break;
      case self::FRAGMENTATION_FIRST: $output = "First Frame"; break;
      case self::FRAGMENTATION_FOLLOWING: $output = "Following Frame"; break;
      default: $output = "unknown"; break;
      }

    return sprintf("%s (0x%02x)", $output, $fragmentation);
    }

  public function setFragBlockNumber($frag_block_number)
    {
    $frag_block_number = intval($frag_block_number);
    if($frag_block_number < 0x00 || $frag_block_number > 0xff)
      throw new MuniZigbeeException("Invalid fragmentation block number field");

    $this->frag_block_number = $frag_block_number;
    }

  public function getFragBlockNumber()
    {
    return $this->frag_block_number;
    }

  public function displayFragBlockNumber()
    {
    return sprintf("0x%02x", $this->getFragBlockNumber());
    }

  public function setFragAckBitfield($frag_ack_bitfield)
    {
    $frag_ack_bitfield = intval($frag_ack_bitfield);
    if($frag_ack_bitfield < 0x00 || $frag_ack_bitfield > 0xff)
      throw new MuniZigbeeException("Invalid fragmentation ack bitfield");

    $this->frag_ack_bitfield = $frag_ack_bitfield;
    }

  public function getFragAckBitfield()
    {
    return $this->frag_ack_bitfield;
    }

  public function displayFragAckBitfield()
    {
    return Buffer::displayBitmap8($this->getFragAckBitfield());
    }

  public function setPayload($payload)
    {
    $this->payload = $payload;
    }

  public function getPayload()
    {
    return $this->payload;
    }

  public function setPayloadObject($object)
    {
    if($object instanceof ZDOFrame)
      {
      $this->setFrameType(self::FRAME_TYPE_DATA);
      $this->setDestinationEndpoint(0x00);
      $this->setProfileId(0x0000);
      $this->setClusterId($object->getCommandId());
      $this->setPayload($object->getFrame());
      return;
      }
    elseif($object instanceof ZCLFrame)
      {
      $this->setFrameType(self::FRAME_TYPE_DATA);
      $this->setPayload($object->getFrame());
      return;
      }

    throw new MuniZigbeeException("Invalid payload object");
    }

  public function getPayloadObject()
    {
    if($this->isZDOPayload())
      return new ZDOFrame($this->getPayload(), $this->getClusterId());
    elseif($this->isZCLPayload())
      return new ZCLFrame($this->getPayload());

    throw new MuniZigbeeException("Could not find payload object");
    }

  public function displayPayload()
    {
    return Buffer::displayOctetString($this->getPayload());
    }

  private function isZDOPayload()
    {
    if($this->getFrameType() === self::FRAME_TYPE_DATA &&
        ($this->getFrameFormat() === self::FRAME_FORMAT_NORMAL &&
         $this->getProfileId() === 0x0000 &&
         $this->getDestinationEndpoint() === 0x00) ||
       $this->getFrameFormat() === self::FRAME_FORMAT_SHORT_ZDO)
      return true;

    return false;
    }

  private function isZCLPayload()
    {
    if($this->getFrameType() === self::FRAME_TYPE_DATA &&
        !($this->getFrameFormat() === self::FRAME_FORMAT_NORMAL &&
          $this->getProfileId() === 0x0000 &&
          $this->getDestinationEndpoint() === 0x00) ||
       $this->getFrameFormat() === self::FRAME_FORMAT_SHORT_ZCL)
      return true;

    return false;
    }

  private function isGroupAddressPresent()
    {
    if($this->isDataFrameOrDataAck() &&
       $this->getFrameType() === self::FRAME_TYPE_DATA &&
       $this->getDeliveryMode() === self::DELIVERY_MODE_GROUP_ADDRESS)
      return true;

    return false;
    }

  private function isDestinationEndpointPresent()
    {
    if($this->isDataFrameOrDataAck() &&
       $this->getDeliveryMode() !== self::DELIVERY_MODE_GROUP_ADDRESS)
      return true;

    return false;
    }

  private function isClusterIdPresent()
    {
    if($this->isDataFrameOrDataAck())
      return true;

    return false;
    }

  private function isProfileIdPresent()
    {
    if($this->isDataFrameOrDataAck())
      return true;

    return false;
    }

  private function isSourceEndpointPresent()
    {
    if($this->isDataFrameOrDataAck())
      return true;

    return false;
    }

  private function isExtHeaderPresent()
    {
    if($this->getFrameType() !== self::FRAME_TYPE_COMMAND &&
       $this->getExtHeaderPresent() === self::EXT_HEADER_IS_PRESENT)
      return true;

    return false;
    }

  private function isDataFrameOrDataAck()
    {
    if($this->getFrameType() === self::FRAME_TYPE_DATA ||
        ($this->getFrameType() === self::FRAME_TYPE_ACKNOWLEDGE && 
         $this->getAckFormat() === self::ACK_FORMAT_DATA))
      return true;

    return false;
    }

  private function isFragBlockNumberPresent()
    {
    if($this->isExtHeaderPresent() &&
       in_array($this->getFragmentation(), array(self::FRAGMENTATION_FIRST, self::FRAGMENTATION_FOLLOWING)))
      return true;

    return false;
    }

  private function isFragAckBitfieldPresent()
    {
    if($this->isFragBlockNumberPresent() &&
       $this->getFrameType() === self::FRAME_TYPE_ACKNOWLEDGE)
      return true;

    return false;
    }

  private function isPayloadPresent()
    {
    if($this->getFrameType() !== self::FRAME_TYPE_ACKNOWLEDGE)
      return true;

    return false;
    }

  public function __toString()
    {
    $short = $this->isShortFrame();

    $output =  "ZigBeeAPSFrame (length: ".strlen($this->getFrame()).", format: ".$this->displayFrameFormat().")".PHP_EOL;
    $output .= "|- frameControl     : ".$this->displayFrameControl().PHP_EOL;
    $output .= "|  |- frameType     : ".$this->displayFrameType().PHP_EOL;
    $output .= "|  |- deliveryMode  : ".$this->displayDeliveryMode().PHP_EOL;
    $output .= "|  |- ackFormat     : ".$this->displayAckFormat().PHP_EOL;
    $output .= "|  |- security      : ".$this->displaySecurity().PHP_EOL;
    $output .= "|  |- ackRequest    : ".$this->displayAckRequest().PHP_EOL;
    $output .= "|  `- extHeaderPres : ".$this->displayExtHeaderPresent().PHP_EOL;

    if(!$short && $this->isDestinationEndpointPresent())
      $output .= "|- destEndpoint     : ".$this->displayDestinationEndpoint().PHP_EOL;

    if(!$short && $this->isGroupAddressPresent())
      $output .= "|- groupAddress     : ".$this->displayGroupAddress().PHP_EOL;

    if($this->isClusterIdPresent())
      $output .= "|- clusterId        : ".$this->displayClusterId().PHP_EOL;

    if(!$short && $this->isProfileIdPresent())
      $output .= "|- profileId        : ".$this->displayProfileId().PHP_EOL;

    if(!$short && $this->isSourceEndpointPresent())
      $output .= "|- sourceEndpoint   : ".$this->displaySourceEndpoint().PHP_EOL;

    if(!$short)
      $output .= "|- apsCounter       : ".$this->displayApsCounter().PHP_EOL;

    if($this->isExtHeaderPresent())
      {
      $output .= "|- extHeader        : ".$this->displayExtHeader().PHP_EOL;
      $output .= "|  |- fragmentation : ".$this->displayFragmentation().PHP_EOL;
      if($this->isFragBlockNumberPresent())
        $output .= "|  |- blockNumber   : ".$this->displayFragBlockNumber().PHP_EOL;

      if($this->isFragAckBitfieldPresent())
        $output .= "|  `- ackBitfield   : ".$this->displayFragAckBitfield().PHP_EOL;
      }

    if($this->isPayloadPresent())
      {
      $output .= "|- payload (length: ".strlen($this->getPayload()).")".PHP_EOL;

      try
        {
        $output .= preg_replace("/^   /", "`- ", preg_replace("/^/m", "   ", $this->getPayloadObject()));
        }
      catch(MuniZigbeeException $e)
        {
        $output .= "`-> ".$this->displayPayload().PHP_EOL;
        }
      }

    return $output;
    }

  }

