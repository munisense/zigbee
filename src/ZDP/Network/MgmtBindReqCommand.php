<?php

namespace Munisense\Zigbee\ZDP\Network;

use Munisense\Zigbee\ZDP\Command;

/**
 * Class MgmtBindReqCommand
 *
 * @package Munisense\Zigbee\ZDP\Network
 *
 * The Mgmt_Bind_req is generated from a Local Device wishing to retrieve the
 * contents of the Binding Table from the Remote Device.
 */
class MgmtBindReqCommand extends AbstractStartIndexReqCommand
  {
  /**
   * Returns the Cluster ID of this frame
   *
   * @return int
   */
  public function getClusterId()
    {
    return Command::COMMAND_MGMT_BIND_REQ;
    }
  }