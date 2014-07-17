<?php

namespace Munisense\Zigbee\ZCL;

use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\MuniZigbeeException;
use Munisense\Zigbee\ZCL\General;


class ZCLFrame extends AbstractFrame
  {
  const FRAME_TYPE_PROFILE_WIDE = 0x00;
  const FRAME_TYPE_CLUSTER_SPECIFIC = 0x01;
  const FRAME_TYPE_RESERVED2 = 0x02;
  const FRAME_TYPE_RESERVED3 = 0x03;

  const MANUFACTURER_ID_NOT_PRESENT = 0x00;
  const MANUFACTURER_ID_IS_PRESENT = 0x01;

  const DIRECTION_SERVER_TO_CLIENT = 0x00;
  const DIRECTION_CLIENT_TO_SERVER = 0x01;

  const DEFAULT_RESPONSE_ENABLED = 0x00;
  const DEFAULT_RESPONSE_DISABLED = 0x01;

  // Frame control
  private $frame_type = 0x00;
  private $manufacturer_id_present = 0x00;
  private $direction = 0x00;
  private $disable_default_response = 0x00;
  private $frame_control_reserved = 0x00;

  private $manufacturer_id = 0x0000;
  private $transaction_id = 0x00;
  private $command_id = 0x00;

  private $payload = "";

  /**
   * Helper method to create a ZCL Frame
   *
   * @param IZCLCommandFrame $payload
   * @param int $manufacturer_id Optional Manufacturer ID
   * @param int $direction Direction
   * @param int $disable_default_response Disable default response
   * @param int $transaction_id Optional Transaction ID
   * @return ZCLFrame The Constructed ZCLFrame
   * @throws MuniZigbeeException If there were problems with composing the ZCL Frame
   */
  public static function construct(IZCLCommandFrame $payload = null, $manufacturer_id = null,
                                   $direction = self::DIRECTION_SERVER_TO_CLIENT,
                                   $disable_default_response = self::DEFAULT_RESPONSE_ENABLED,
                                   $transaction_id = null)
    {
    $frame = new self;

    if($manufacturer_id !== null)
      {
      $frame->setManufacturerIdPresent(true);
      $frame->setManufacturerId($manufacturer_id);
      }

    $frame->setDirection($direction);
    $frame->setDisableDefaultResponse($disable_default_response);

    if($transaction_id !== null)
      $frame->setTransactionId($transaction_id);

    if($payload !== null)
      $frame->setPayloadObject($payload);

    return $frame;
    }

  public function setFrame($frame)
    {
    $this->setFrameControl(Buffer::unpackInt8u($frame));

    if($this->getManufacturerIdPresent())
      $this->setManufacturerId(Buffer::unpackInt16u($frame));

    $this->setTransactionId(Buffer::unpackInt8u($frame));
    $this->setCommandId(Buffer::unpackInt8u($frame));
    $this->setPayload($frame);
    }

  public function getFrame()
    {
    $frame = "";

    $frame .= $this->getZclHeaderFrame();
    $frame .= $this->getPayload();

    return $frame;
    }

  public function getZclHeaderFrame()
    {
    $frame = "";

    Buffer::packInt8u($frame, $this->getFrameControl());

    if($this->getManufacturerIdPresent())
      Buffer::packInt16u($frame, $this->getManufacturerId());

    Buffer::packInt8u($frame, $this->getTransactionId());
    Buffer::packInt8u($frame, $this->getCommandId());

    return $frame;
    }

  public function setFrameControl($frame_control)
    {
    $frame_control = intval($frame_control);

    if($frame_control < 0x00 || $frame_control > 0xff)
      throw new MuniZigbeeException("Invalid frame control");

    $this->setFrameType(($frame_control >> 0) & 0x03);
    $this->setManufacturerIdPresent(($frame_control >> 2) & 0x01);
    $this->setDirection(($frame_control >> 3) & 0x01);
    $this->setDisableDefaultResponse(($frame_control >> 4) & 0x01);
    $this->frame_control_reserved = ($frame_control >> 5) & 0x0f;
    }

  public function getFrameControl()
    {
    return ($this->getFrameType()               & 0x03) << 0 |
           ($this->getManufacturerIdPresent()   & 0x01) << 2 |
           ($this->getDirection()               & 0x01) << 3 |
           ($this->getDisableDefaultResponse()  & 0x01) << 4 |
           ($this->frame_control_reserved       & 0x0f) << 5;
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
      case self::FRAME_TYPE_PROFILE_WIDE: $output = "Profile Wide"; break;
      case self::FRAME_TYPE_CLUSTER_SPECIFIC: $output = "Cluster Specific"; break;
      case self::FRAME_TYPE_RESERVED2: $output = "Reserved"; break;
      case self::FRAME_TYPE_RESERVED3: $output = "Reserved"; break;
      default: $output = "unknown"; break;
      }

    return sprintf("%s (0x%02x)", $output, $frame_type);
    }

  public function setManufacturerIdPresent($manufacturer_id_present)
    {
    $this->manufacturer_id_present = $manufacturer_id_present ? self::MANUFACTURER_ID_IS_PRESENT : self::MANUFACTURER_ID_NOT_PRESENT;
    }

  public function getManufacturerIdPresent()
    {
    return $this->manufacturer_id_present;
    }

  public function displayManufacturerIdPresent()
    {
    $manufacturer_id_present = $this->getManufacturerIdPresent();
    switch($manufacturer_id_present)
      {
      case self::MANUFACTURER_ID_NOT_PRESENT: $output = "Not Present"; break;
      case self::MANUFACTURER_ID_IS_PRESENT: $output = "Present"; break;
      default: $output = "unknown"; break;
      }

    return sprintf("%s (0x%02x)", $output, $manufacturer_id_present);
    }

  public function setDirection($direction)
    {
    $this->direction = $direction ? self::DIRECTION_CLIENT_TO_SERVER : self::DIRECTION_SERVER_TO_CLIENT;
    }

  public function getDirection()
    {
    return $this->direction;
    }

  public function displayDirection()
    {
    $direction = $this->getDirection();
    switch($direction)
      {
      case self::DIRECTION_CLIENT_TO_SERVER: $output = "Client->Server"; break;
      case self::DIRECTION_SERVER_TO_CLIENT: $output = "Server->Client"; break;
      default: $output = "unknown"; break;
      }

    return sprintf("%s (0x%02x)", $output, $direction);
    }

  public function setDisableDefaultResponse($disable_default_response)
    {
    $this->disable_default_response = $disable_default_response ? self::DEFAULT_RESPONSE_DISABLED : self::DEFAULT_RESPONSE_ENABLED;
    }

  public function getDisableDefaultResponse()
    {
    return $this->disable_default_response;
    }

  public function displayDefaultResponse()
    {
    $default_response = $this->getDisableDefaultResponse();
    switch($default_response)
      {
      case self::DEFAULT_RESPONSE_DISABLED: $output = "Disabled"; break;
      case self::DEFAULT_RESPONSE_ENABLED: $output = "Enabled"; break;
      default: $output = "unknown"; break;
      }

    return sprintf("%s (0x%02x)", $output, $default_response);
    }

  public function setManufacturerId($manufacturer_id)
    {
    $manufacturer_id = intval($manufacturer_id);
    if($manufacturer_id < 0x00 || $manufacturer_id > 0xffff)
      throw new MuniZigbeeException("Invalid manufacturer id");

    $this->manufacturer_id = $manufacturer_id;
    }

  public function getManufacturerId()
    {
    return $this->manufacturer_id;
    }

  public function displayManufacturerId()
    {
    return sprintf("0x%04x", $this->getManufacturerId());
    }

  public function setTransactionId($transaction_id)
    {
    $transaction_id = intval($transaction_id);
    if($transaction_id < 0x00 || $transaction_id > 0xff)
      throw new MuniZigbeeException("Invalid transaction id");

    $this->transaction_id = $transaction_id;
    }

  public function getTransactionId()
    {
    return $this->transaction_id;
    }

  public function displayTransactionId()
    {
    return sprintf("0x%02x", $this->getTransactionId());
    }

  public function setCommandId($command_id)
    {
    $command_id = intval($command_id);
    if($command_id < 0x00 || $command_id > 0xff)
      throw new MuniZigbeeException("Invalid command id");

    $this->command_id = $command_id;
    }

  public function getCommandId()
    {
    return $this->command_id;
    }

  public function displayCommandId()
    {
    if($this->getFrameType() == self::FRAME_TYPE_PROFILE_WIDE)
      return General\GeneralCommand::displayCommand($this->getCommandId());

    return sprintf("0x%02x", $this->getCommandId());
    }

  public function setPayload($payload)
    {
    $this->payload = $payload;
    }

  public function getPayload()
    {
    return $this->payload;
    }

  /**
   * Sets the payload object, and using it's class
   * to find the Command ID and the Frame Type.
   *
   * @param IZCLCommandFrame $frame
   */
  public function setPayloadObject(IZCLCommandFrame $frame)
    {
    $this->setFrameType($frame->getFrameType());
    $this->setCommandId($frame->getCommandId());
    $this->setPayload($frame->getFrame());
    }

  public function getPayloadObject($cluster_id = null)
    {
    $class_name = $this->findClassOfPayload($this->getCommandId(), $this->getFrameType(), $cluster_id);
    return new $class_name($this->getPayload());
    }

  /**
   * This method tries to find a class for a given cluster and command_id
   *
   * @param $command_id
   * @param int $frame_type
   * @param int $cluster_id
   * @return string Classname
   * @throws MuniZigbeeException When no class could be found
   */
  protected function findClassOfPayload($command_id, $frame_type = self::FRAME_TYPE_PROFILE_WIDE, $cluster_id = null)
    {
    if($frame_type == self::FRAME_TYPE_PROFILE_WIDE)
      {
      if(isset(General\GeneralCommand::$command[$command_id]))
        return General\GeneralCommand::$command[$command_id]["class"];
      }
    // If we know the cluster, then we can look in the cluster specific commands
    elseif($cluster_id != null)
      {
      if($cluster_id == Cluster::IAS_Zone)
        if(isset(IAS_Zone\ClusterSpecificCommand::$command[$command_id]))
          return IAS_Zone\ClusterSpecificCommand::$command[$command_id]["class"];
      }

    throw new MuniZigbeeException("Payload class for command ID ".$this->displayCommandId()." not found");
    }

  public function displayPayload()
    {
    return Buffer::displayOctetString($this->getPayload());
    }

  public function __toString()
    {
    $output =  "ZCLFrame (length: ".strlen($this->getFrame()).")".PHP_EOL;
    $output .= "|- FrameControl     : ".$this->displayFrameControl().PHP_EOL;
    $output .= "|  |- FrameType     : ".$this->displayFrameType().PHP_EOL;
    $output .= "|  |- ManufIdPres   : ".$this->displayManufacturerIdPresent().PHP_EOL;
    $output .= "|  |- Direction     : ".$this->displayDirection().PHP_EOL;
    $output .= "|  `- DefaultResp   : ".$this->displayDefaultResponse().PHP_EOL;
    if($this->getManufacturerIdPresent())
      $output .= "|- ManufacturerId   : ".$this->displayManufacturerId().PHP_EOL;
    $output .= "|- TransactionId    : ".$this->displayTransactionId().PHP_EOL;
    $output .= "|- CommandId        : ".$this->displayCommandId().PHP_EOL;
    $output .= "|- Payload (length: ".strlen($this->getPayload()).")".PHP_EOL;

    try
      {
      $output .= preg_replace("/^   /", "`- ", preg_replace("/^/m", "   ", $this->getPayloadObject()));
      }
    catch(\Exception $e)
      {
      $output .= "`-> ".$this->displayPayload().PHP_EOL;
      }

    return $output;
    }
  }

