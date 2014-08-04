<?php

namespace Munisense\Zigbee\ZCL\General;

use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Exception\ZigbeeException;
use Munisense\Zigbee\ZCL\IZCLCommandFrame;
use Munisense\Zigbee\ZCL\ZCLFrame;

/**
 * Class ReadAttributesStructuredCommand
 * @package Munisense\Zigbee\ZCL\General
 *
 * TODO Implement this class
 */
class ReadAttributesStructuredCommand extends AbstractFrame implements IZCLCommandFrame
  {
  public function setFrame($frame)
    {
    throw new ZigbeeException(__CLASS__." is not yet implemented");
    }

  public function getFrame()
    {
    throw new ZigbeeException(__CLASS__." is not yet implemented");
    }

  /**
   * Returns the Command ID of this frame
   * @return int
   */
  public function getCommandId()
    {
    return GeneralCommand::READ_ATTRIBUTES_STRUCTURED;
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

