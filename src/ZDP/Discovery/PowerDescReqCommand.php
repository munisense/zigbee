<?php

namespace Munisense\Zigbee\ZDP\Discovery;
use Munisense\Zigbee\ZDP\Command;

/**
 * Class PowerDescReqCommand
 *
 * @package Munisense\Zigbee\ZDP\Discovery
 *
 * The Power_Desc_req command is generated from a local device wishing to
 * inquire as to the power descriptor of a remote device.
 */
class PowerDescReqCommand extends AbstractNWKAddrOfInterestReqCommand
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
    return Command::COMMAND_POWER_DESC_REQ;
    }
  }

