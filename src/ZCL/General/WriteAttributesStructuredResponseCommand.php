<?php

namespace Munisense\Zigbee\ZCL\General;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Exception\MuniZigbeeException;
use Munisense\Zigbee\ZCL\IZCLCommandFrame;
use Munisense\Zigbee\ZCL\ZCLFrame;

/**
 * Class WriteAttributesStructuredResponseCommand
 * @package Munisense\Zigbee\ZCL\General
 *
 * TODO Implement this class
 */
class WriteAttributesStructuredResponseCommand extends AbstractFrame implements IZCLCommandFrame
  {
  public function setFrame($frame)
    {
    throw new MuniZigbeeException(__CLASS__." is not yet implemented");
    }

  public function getFrame()
    {
    throw new MuniZigbeeException(__CLASS__." is not yet implemented");
    }

  /**
   * Returns the Command ID of this frame
   * @return int
   */
  public function getCommandId()
    {
    return GeneralCommand::WRITE_ATTRIBUTES_STRUCTURED_RESPONSE;
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

