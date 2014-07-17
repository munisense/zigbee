<?php

namespace Munisense\Zigbee\ZCL\General;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Exception\MuniZigbeeException;
use Munisense\Zigbee\ZCL\IZCLCommandFrame;
use Munisense\Zigbee\ZCL\ZCLFrame;

/**
 * Class WriteAttributesUndividedCommand
 * @package Munisense\Zigbee\ZCL\General
 *
 * The write attributes undivided command is generated when a device wishes to
 * change the values of one or more attributes located on another device, in such a
 * way that if any attribute cannot be written (e.g. if an attribute is not implemented
 * on the device, or a value to be written isoutside its valid range), no attribute
 * values are changed.
 * In all other respects, including generation of a write attributes response command,
 * the format and operation of the command isthe same as that of the write attributes
 * command, except that the command identifier field shall be set to indicate the
 * write attributes undivided command (see Table 2.9).
 */
class WriteAttributesUndividedCommand extends WriteAttributesCommand implements IZCLCommandFrame
  {
  /**
   * Returns the Command ID of this frame
   * @return int
   */
  public function getCommandId()
    {
    return GeneralCommand::WRITE_ATTRIBUTES_UNDIVIDED;
    }
  }

