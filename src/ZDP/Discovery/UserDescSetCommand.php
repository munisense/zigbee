<?php

namespace Munisense\Zigbee\ZDP\Discovery;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\ZigbeeException;
use Munisense\Zigbee\ZDP\Command;
use Munisense\Zigbee\ZDP\IZDPCommandFrame;

/**
 * Class UserDescSetCommand
 *
 * @package Munisense\Zigbee\ZDP\Discovery
 *
 * The User_Desc_set command is generated from a local device wishing to
 * configure the user descriptor on a remote device.
 */
class UserDescSetCommand extends AbstractFrame implements IZDPCommandFrame
  {
  private $nwk_addr_of_interest;

  /**
   * @var UserDescriptor $user_descriptor
   */
  private $user_descriptor;

  public static function construct($nwk_addr_of_interest, UserDescriptor $user_descriptor)
    {
    $frame = new self;
    $frame->setNwkAddrOfInterest($nwk_addr_of_interest);
    $frame->setUserDescriptor($user_descriptor);
    return $frame;
    }

  public function setFrame($frame)
    {
    $this->setNwkAddrOfInterest(Buffer::unpackInt16u($frame));

    // User Descriptor Length, unused, but may not remain in buffer
    Buffer::unpackInt8u($frame);
    $this->setUserDescriptor(new UserDescriptor($frame));
    }

  public function getFrame()
    {
    $frame = "";

    Buffer::packInt16u($frame, $this->getNwkAddrOfInterest());

    $user_descr_frame = $this->getUserDescriptor()->getFrame();
    Buffer::packInt8u($frame, strlen($user_descr_frame));
    $frame .= $user_descr_frame;

    return $frame;
    }

  /**
   * @return int
   */
  public function getNwkAddrOfInterest()
    {
    return $this->nwk_addr_of_interest;
    }

  /**
   * @param $nwk_address
   * @throws \Munisense\Zigbee\Exception\ZigbeeException
   */
  public function setNwkAddrOfInterest($nwk_address)
    {
    if($nwk_address >= 0x0000 && $nwk_address <= 0xffff)
      $this->nwk_addr_of_interest = $nwk_address;
    else
      throw new ZigbeeException("Invalid nwk address");
    }

  public function displayNwkAddrOfInterest()
    {
    return Buffer::displayInt16u($this->getNwkAddrOfInterest());
    }

  /**
   * @return UserDescriptor
   */
  public function getUserDescriptor()
    {
    return $this->user_descriptor;
    }

  /**
   * @param UserDescriptor $user_descriptor
   */
  public function setUserDescriptor($user_descriptor)
    {
    $this->user_descriptor = $user_descriptor;
    }

  public function __toString()
    {
    $output = __CLASS__." (length: ".strlen($this->getFrame()).")".PHP_EOL;
    $output .= "|- NwkAddr     : ".$this->displayNwkAddrOfInterest().PHP_EOL;
    $output .= preg_replace("/^   /", "`- ", preg_replace("/^/m", "   ", $this->getUserDescriptor()));

    return $output;
    }

  /**
   * Returns the Cluster ID of this frame
   *
   * @return int
   */
  public function getClusterId()
    {
    return Command::COMMAND_USER_DESC_SET;
    }
  }

