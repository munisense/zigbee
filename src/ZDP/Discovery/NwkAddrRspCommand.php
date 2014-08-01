<?php

namespace Munisense\Zigbee\ZDP\Discovery;
use Munisense\Zigbee\ZDP\Command;

/**
 * Class NwkAddrRspCommand
 * @package Munisense\Zigbee\ZDP\Discovery
 */
class NwkAddrRspCommand extends AbstractAddrRspCommand
  {
  /**
   * Returns the Cluster ID of this frame
   * @return int
   */
  public function getClusterId()
    {
    return Command::COMMAND_NWK_ADDR_RSP;
    }
  }

