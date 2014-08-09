<?php

namespace Munisense\Zigbee\ZDP\Network;

use Munisense\Zigbee\ZDP\Command;

/**
 * Class MgmtCacheReqCommand
 *
 * @package Munisense\Zigbee\ZDP\Network
 *
 * The Mgmt_Cache_req is provided to enable ZigBee devices on the network to
 * retrieve a list of ZigBee End Devices registered with a Primary Discovery Cache
 * device.
 */
class MgmtCacheReqCommand extends AbstractStartIndexReqCommand
  {
  /**
   * Returns the Cluster ID of this frame
   *
   * @return int
   */
  public function getClusterId()
    {
    return Command::COMMAND_MGMT_CACHE_REQ;
    }
  }