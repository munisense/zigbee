<?php

namespace Munisense\Zigbee\ZDO;

/**
 * Class IEEEAddrRspCommand
 * @package Munisense\Zigbee\ZDO
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

