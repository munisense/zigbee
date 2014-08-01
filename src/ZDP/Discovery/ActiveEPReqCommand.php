<?php

namespace Munisense\Zigbee\ZDP\Discovery;
use Munisense\Zigbee\ZDP\Command;

/**
 * Class ActiveEPReqCommand
 *
 * @package Munisense\Zigbee\ZDP\Discovery
 *
 * The Active_EP_req command is generated from a local device wishing to acquire
 * the list of endpoints on a remote devicewith simple descriptors.
 */
class ActiveEPReqCommand extends AbstractNWKAddrOfInterestReqCommand
  {
  public static function construct($nwk_address_of_interest)
    {
    $frame = new static;
    $frame->setNwkAddressOfInterest($nwk_address_of_interest);
    return $frame;
    }

  /**
   * Returns the Cluster ID of this frame
   * @return int
   */
  public function getClusterId()
    {
    return Command::COMMAND_ACTIVE_EP_REQ;
    }
  }

