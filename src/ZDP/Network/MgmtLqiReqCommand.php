<?php

namespace Munisense\Zigbee\ZDP\Network;

use Munisense\Zigbee\ZDP\Command;

/**
 * Class MgmtLqiReqCommand
 *
 * @package Munisense\Zigbee\ZDP\Network
 *
 * The Mgmt_Lqi_req is generated from a Local Device wishing to obtain a
 * neighbor list for the Remote Device along with associated LQI values to each
 * neighbor.
 */
class MgmtLqiReqCommand extends AbstractStartIndexReqCommand
  {
  /**
   * Returns the Cluster ID of this frame
   *
   * @return int
   */
  public function getClusterId()
    {
    return Command::COMMAND_MGMT_LQI_REQ;
    }
  }