<?php

namespace Munisense\Zigbee\ZDP\Discovery;
use Munisense\Zigbee\ZDP\Command;

/**
 * Class UserDescReqCommand
 *
 * @package Munisense\Zigbee\ZDP\Discovery
 *
 * The User_Desc_req command isgenerated from a local device wishing to inquire
 * as to the user descriptor of a remote device.
 */
class UserDescReqCommand extends AbstractNWKAddrOfInterestReqCommand
  {
  public static function construct($nwk_address_of_interest)
    {
    $frame = new self;
    $frame->setNwkAddressOfInterest($nwk_address_of_interest);
    return $frame;
    }

  /**
   * Returns the Cluster ID of this frame
   * @return int
   */
  public function getClusterId()
    {
    return Command::COMMAND_USER_DESC_REQ;
    }
  }

