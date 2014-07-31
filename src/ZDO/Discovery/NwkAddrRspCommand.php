<?php

namespace Munisense\Zigbee\ZDO\Discovery;
use Munisense\Zigbee\ZDO\Command;

/**
 * Class NwkAddrRspCommand
 * @package Munisense\Zigbee\ZDO\Discovery
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

