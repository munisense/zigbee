<?php

namespace Munisense\Zigbee\ZCL\General;

use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\ZCL\IZCLCommandFrame;
use Munisense\Zigbee\ZCL\ZCLFrame;
use Munisense\Zigbee\ZCL\ZCLStatus;

class DefaultResponseCommand extends AbstractFrame implements IZCLCommandFrame
  {
  private $command_identifier;
  private $status_code;

  public static function construct($command_identifier, $status_code)
    {
    $frame = new self;
    $frame->command_identifier = $command_identifier;
    $frame->status_code = $status_code;
    return $frame;
    }

  public function setFrame($frame)
    {
    $this->setCommandIdentifier(Buffer::unpackInt8u($frame));
    $this->setStatusCode(Buffer::unpackInt8u($frame));
    }

  public function getFrame()
    {
    $frame = "";

    Buffer::packInt8u($frame, $this->command_identifier);
    Buffer::packInt8u($frame, $this->status_code);

    return $frame;
    }

  /**
   * @param int $command_identifier
   */
  public function setCommandIdentifier($command_identifier)
    {
    $this->command_identifier = $command_identifier;
    }

  /**
   * @return int
   */
  public function getCommandIdentifier()
    {
    return $this->command_identifier;
    }

  public function displayCommandIdentifier()
    {
    return sprintf("0x%02x", $this->command_identifier);
    }

  /**
   * @param int $status_code
   */
  public function setStatusCode($status_code)
    {
    $this->status_code = $status_code;
    }

  /**
   * @return int
   */
  public function getStatusCode()
    {
    return $this->status_code;
    }

  public function displayStatusCode()
    {
    return ZCLStatus::displayStatus($this->status_code);
    }

  public function __toString()
    {
    return  __CLASS__." (commandIdentifier: ".$this->displayCommandIdentifier().", status: ".$this->displayStatusCode().")".PHP_EOL;
    }


  /**
   * Returns the Command ID of this frame
   * @return int
   */
  public function getCommandId()
    {
    return GeneralCommand::DEFAULT_RESPONSE;
    }

  /**
   * Returns the Frame Type of this frame
   * @return int
   */
  public function getFrameType()
    {
    return ZCLFrame::FRAME_TYPE_PROFILE_WIDE;
    }
  }

