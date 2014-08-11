<?php

namespace Munisense\Zigbee\ZDP\Network;

use Munisense\Zigbee\ZDP\Command;

/**
 * Class MgmtRtgReqCommand
 *
 * @package Munisense\Zigbee\ZDP\Network
 *
 * The Mgmt_Rtg_req is generated from a Local Device wishing to retrieve the
 * contents of the Routing Table from the Remote Device.
 */
class MgmtRtgReqCommand extends AbstractStartIndexReqCommand
  {
  /**
   * Returns the Cluster ID of this frame
   *
   * @return int
   */
  public function getClusterId()
    {
    return Command::COMMAND_MGMT_RTG_REQ;
    }
  }