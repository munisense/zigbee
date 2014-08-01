<?php

namespace Munisense\Zigbee\ZDP\Discovery;
use Munisense\Zigbee\ZDP\Command;

/**
 * Class IEEEAddrRspCommand
 * @package Munisense\Zigbee\ZDP\Discovery
 */
class IEEEAddrRspCommand extends AbstractAddrRspCommand
  {
  /**
   * Returns the Cluster ID of this frame
   * @return int
   */
  public function getClusterId()
    {
    return Command::COMMAND_IEEE_ADDR_RSP;
    }
  }

