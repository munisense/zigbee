<?php

namespace Munisense\Zigbee\ZDO;

/**
 * Class NodeDescReqCommand
 *
 * @package Munisense\Zigbee\ZDO
 *
 * The Node_Desc_req command is generated from a local device wishing to inquire
 * as to the node descriptor of a remote device
 */
class NodeDescReqCommand extends AbstractNWKAddrOfInterestReqCommand
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
    return Command::COMMAND_NODE_DESC_REQ;
    }
  }

