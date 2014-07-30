<?php

namespace Munisense\Zigbee\ZDO;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\MuniZigbeeException;

/**
 * Class SimpleDescReqCommand
 *
 * @package Munisense\Zigbee\ZDO
 *
 * The Simple_Desc_req command is generated from a local device wishing to
 * inquire as to the simple descriptor of a remote device on a specified endpoint.
 */
class SimpleDescReqCommand extends AbstractNWKAddrOfInterestReqCommand
  {
  private $endpoint;

  public static function construct($nwk_addr_of_interest, $endpoint)
    {
    $frame = new self;
    $frame->setNwkAddressOfInterest($nwk_addr_of_interest);
    $frame->setEndpoint($endpoint);
    return $frame;
    }

  public function setFrame($frame)
    {
    $this->setNwkAddressOfInterest(Buffer::unpackInt16u($frame));
    $this->setEndpoint(Buffer::unpackInt8u($frame));
    }

  public function getFrame()
    {
    $frame = parent::getFrame();
    Buffer::packInt8u($frame, $this->getEndpoint());
    return $frame;
    }

  public function __toString()
    {
    $output = __CLASS__." (length: ".strlen($this->getFrame()).")".PHP_EOL;
    $output .= "|- NwkAddr    : ".$this->displayNwkAddressOfInterest().PHP_EOL;
    $output .= "`- Endpoint    : ".$this->displayEndpoint().PHP_EOL;

    return $output;
    }

  /**
   * @return int
   */
  public function getEndpoint()
    {
    return $this->endpoint;
    }

  /**
   * @param int $endpoint
   * @throws \Munisense\Zigbee\Exception\MuniZigbeeException
   */
  public function setEndpoint($endpoint)
    {
    if($endpoint >= 1 && $endpoint <= 240)
      $this->endpoint = $endpoint;
    else
      throw new MuniZigbeeException("Endpoint must be between 1 and 240");
    }

  public function displayEndpoint()
    {
    return Buffer::displayInt8u($this->getEndpoint());
    }

  /**
   * Returns the Cluster ID of this frame
   * @return int
   */
  public function getClusterId()
    {
    return Command::COMMAND_SIMPLE_DESC_REQ;
    }
  }

