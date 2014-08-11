<?php

namespace Munisense\Zigbee\ZDP\Network;

use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\ZigbeeException;

class NeighborDescriptor extends AbstractFrame
  {
  private $extended_pan_id;

  private $extended_address;

  private $network_address;

  private $device_type;
  const ZIGBEE_COORDINATOR  = 0x00;
  const ZIGBEE_ROUTER       = 0x01;
  const ZIGBEE_END_DEVICE   = 0x02;
  const DEVICE_TYPE_UNKNOWN = 0x03;

  private $rx_on_when_idle;
  const RECEIVER_OFF_WHEN_IDLE = 0x00;
  const RECEIVER_ON_WHEN_IDLE  = 0x01;
  const RECEIVER_UNKNOWN       = 0x02;

  private $relationship;
  const RELATION_NEIGHBOR_IS_PARENT  = 0x00;
  const RELATION_NEIGHBOR_IS_CHILD   = 0x02;
  const RELATION_NEIGHBOR_IS_SIBLING = 0x02;
  const RELATION_NONE_OF_THE_ABOVE   = 0x03;
  const RELATION_PREVIOUS_CHILD      = 0x04;

  private $permit_joining;
  const NEIGHBOR_IS_NOT_ACCEPTING_JOIN_REQUESTS = 0x00;
  const NEIGHBOR_IS_ACCEPTING_JOIN_REQUESTS     = 0x01;
  const NEIGHBOR_ACCEPTS_JOIN_REQUESTS_UNKNOWN  = 0x02;
  private $depth;
  private $lqi;

  public static function construct($extended_pan_id, $extended_address, $network_address, $device_type, $rx_on_when_idle, $relationship,
                                   $permit_joining, $depth, $lqi)
    {
    $frame = new self;
    $frame->setExtendedPanId($extended_pan_id);
    $frame->setExtendedAddress($extended_address);
    $frame->setNetworkAddress($network_address);
    $frame->setDeviceType($device_type);
    $frame->setRxOnWhenIdle($rx_on_when_idle);
    $frame->setRelationship($relationship);
    $frame->setPermitJoining($permit_joining);
    $frame->setDepth($depth);
    $frame->setLqi($lqi);
    return $frame;
    }

  /**
   * Returns the frame as a sequence of bytes.
   *
   * @return string $frame
   */
  function getFrame()
    {
    $frame = "";

    Buffer::packEui64($frame, $this->getExtendedPanId());
    Buffer::packEui64($frame, $this->getExtendedAddress());
    Buffer::packInt16u($frame, $this->getNetworkAddress());

    $byte1 = $this->getDeviceType() & 3;
    $byte1 |= ($this->getRxOnWhenIdle() << 2) & 12;
    $byte1 |= ($this->getRelationship() << 5) & 112;
    Buffer::packInt8u($frame, $byte1);

    $byte2 = $this->getPermitJoining() & 3;
    Buffer::packInt8u($frame, $byte2);

    Buffer::packInt8u($frame, $this->getDepth());
    Buffer::packInt8u($frame, $this->getLqi());

    return $frame;
    }

  /**
   * @param string $frame
   */
  function setFrame($frame)
    {
    $this->consumeFrame($frame);
    }

  public function consumeFrame(&$frame)
    {
    $this->setExtendedPanId(Buffer::unpackEui64($frame));
    $this->setExtendedAddress(Buffer::unpackEui64($frame));
    $this->setNetworkAddress(Buffer::unpackInt16u($frame));

    $byte1 = Buffer::unpackInt8u($frame);
    $this->setDeviceType(($byte1 & 3) >> 0);
    $this->setRxOnWhenIdle(($byte1 & 12) >> 2);
    $this->setRelationship(($byte1 & 112) >> 5);

    $byte2 = Buffer::unpackInt8u($frame);
    $this->setPermitJoining($byte2 & 3);

    $this->setDepth(Buffer::unpackInt8u($frame));
    $this->setLqi(Buffer::unpackInt8u($frame));
    }

  /**
   * The tree depth of the neighbor device. A value of 0x00 indicates
   * that the device is the ZigBee coordinator for the network.
   *
   * @return int
   */
  public function getDepth()
    {
    return $this->depth;
    }

  /**
   * The tree depth of the neighbor device. A value of 0x00 indicates
   * that the device is the ZigBee coordinator for the network.
   *
   * @param int $depth
   *
   * @throws ZigbeeException
   */
  public function setDepth($depth)
    {
    if($depth >= 0x00 && $depth <= 0xff)
      $this->depth = $depth;
    else
      throw new ZigbeeException("Depth must be in range of 0x00 - 0xff: " . sprintf("0x%02x", $depth));
    }

  public function displayDepth()
    {
    return sprintf("0x%02x", $this->getDepth());
    }

  /**
   * The type of the neighbor device:
   * 0x0 = ZigBee coordinator
   * 0x1 = ZigBee router
   * 0x2 = ZigBee end device
   * 0x3 = Unknown
   *
   * @return int
   */
  public function getDeviceType()
    {
    return $this->device_type;
    }

  /**
   * The type of the neighbor device:
   * 0x0 = ZigBee coordinator
   * 0x1 = ZigBee router
   * 0x2 = ZigBee end device
   * 0x3 = Unknown
   *
   * @param int $device_type
   * @throws ZigbeeException
   */
  public function setDeviceType($device_type)
    {
    if(in_array($device_type, [self::ZIGBEE_COORDINATOR, self::ZIGBEE_END_DEVICE, self::ZIGBEE_ROUTER, self::DEVICE_TYPE_UNKNOWN]))
      $this->device_type = $device_type;
    else
      throw new ZigbeeException("Invalid Device Type supplied: ".$device_type);
    }

  /**
   * @return string
   */
  public function displayDeviceType()
    {
    switch($this->getDeviceType())
      {
      case self::ZIGBEE_COORDINATOR: return "ZIGBEE_COORDINATOR";
      case self::ZIGBEE_ROUTER: return "ZIGBEE_ROUTER";
      case self::ZIGBEE_END_DEVICE: return "ZIGBEE_END_DEVICE";
      default: return "UNKNOWN";
      }
    }

  /**
   * 64-bit IEEE address that is unique to every device. If this value is
   * unknown at the time of the request, this field shall be set to
   * 0xffffffffffffffff.
   *
   * @return string
   */
  public function getExtendedAddress()
    {
    return $this->extended_address;
    }

  /**
   * 64-bit IEEE address that is unique to every device. If this value is
   * unknown at the time of the request, this field shall be set to
   * 0xffffffffffffffff.
   *
   * @param string $extended_address
   * @throws ZigbeeException
   */
  public function setExtendedAddress($extended_address)
    {
    $this->extended_address = $extended_address;
    }

  /**
   * @return string Hexadecimal representation of Extended Address
   */
  public function displayExtendedAddress()
    {
    return Buffer::displayEui64($this->getExtendedAddress());
    }

  /**
   * The 64-bit extended PAN identifier of the neighboring device.
   *
   * @return string
   */
  public function getExtendedPanId()
    {
    return $this->extended_pan_id;
    }

  /**
   * The 64-bit extended PAN identifier of the neighboring device.
   *
   * @param string $extended_pan_id
   */
  public function setExtendedPanId($extended_pan_id)
    {
    $this->extended_pan_id = $extended_pan_id;
    }

  public function displayExtendedPanId()
    {
    return Buffer::displayEui64($this->getExtendedPanId());
    }

  /**
   * The estimated link quality for RF transmissions from this device. See
   * [B1] for discussion of how this is calculated.
   *
   * @return int
   */
  public function getLqi()
    {
    return $this->lqi;
    }

  /**
   * The estimated link quality for RF transmissions from this device. See
   * [B1] for discussion of how this is calculated.
   *
   * @param int $lqi
   * @throws ZigbeeException
   */
  public function setLqi($lqi)
    {
    if($lqi >= 0x00 && $lqi <= 0xff)
      $this->lqi = $lqi;
    else
      throw new ZigbeeException("LQI must be in range of 0x00 - 0xff: " . sprintf("0x%02x", $lqi));
    }

  /**
   * @return string Hexadecimal representation of LQI
   */
  public function displayLQI()
    {
    return sprintf("0x%02x", $this->getLqi());
    }

  /**
   * The 16-bit network address of the neighboring device.
   *
   * @param int $network_address
   *
   * @throws ZigbeeException
   */
  public function setNetworkAddress($network_address)
    {
    if($network_address >= 0x0000 && $network_address <= 0xffff)
      $this->network_address = $network_address;
    else
      throw new ZigbeeException("Network Address not in range 0x0000 - 0xffff: ".sprintf("0x%04x", $network_address));
    }

  /**
   * The 16-bit network address of the neighboring device.
   *
   * @return int
   */
  public function getNetworkAddress()
    {
    return $this->network_address;
    }

  /**
   * @return string Hexadecimal representation of Network Address
   */
  public function displayNetworkAddress()
    {
    return sprintf("0x%04x", $this->getNetworkAddress());
    }

  /**
   * An indication of whether the neighbor device isaccepting join requests:
   * 0x0 = neighbor is not accepting join requests
   * 0x1 = neighbor is accepting join requests
   * 0x2 = unknown
   *
   * @return int
   */
  public function getPermitJoining()
    {
    return $this->permit_joining;
    }

  /**
   * An indication of whether the neighbor device isaccepting join requests:
   * 0x0 = neighbor is not accepting join requests
   * 0x1 = neighbor is accepting join requests
   * 0x2 = unknown
   *
   * @param int $permit_joining
   * @throws ZigbeeException
   */
  public function setPermitJoining($permit_joining)
    {
    if(in_array($permit_joining, [self::NEIGHBOR_ACCEPTS_JOIN_REQUESTS_UNKNOWN, self::NEIGHBOR_IS_ACCEPTING_JOIN_REQUESTS, self::NEIGHBOR_IS_NOT_ACCEPTING_JOIN_REQUESTS]))
      $this->permit_joining = $permit_joining;
    else
      throw new ZigbeeException("Invalid value for Permit Joining");
    }

  public function displayPermitJoining()
    {
    switch($this->getPermitJoining())
      {
      case self::NEIGHBOR_IS_ACCEPTING_JOIN_REQUESTS: return "NEIGHBOR_IS_ACCEPTING_JOIN_REQUESTS";
      case self::NEIGHBOR_IS_NOT_ACCEPTING_JOIN_REQUESTS: return "NEIGHBOR_IS_NOT_ACCEPTING_JOIN_REQUESTS";
      default: return "NEIGHBOR_ACCEPTS_JOIN_REQUESTS_UNKNOWN";
      }
    }

  /**
   * The relationship between the neighbor and the current device:
   * 0x0 = neighbor is the parent
   * 0x1 = neighbor is a child
   * 0x2 = neighbor is a sibling
   * 0x3 = None of the above
   * 0x4 = previous child
   *
   * @return int
   */
  public function getRelationship()
    {
    return $this->relationship;
    }

  /**
   * The relationship between the neighbor and the current device:
   * 0x0 = neighbor is the parent
   * 0x1 = neighbor is a child
   * 0x2 = neighbor is a sibling
   * 0x3 = None of the above
   * 0x4 = previous child
   *
   * @param int $relationship
   *
   * @throws ZigbeeException
   */
  public function setRelationship($relationship)
    {
    if(in_array($relationship, [self::RELATION_NONE_OF_THE_ABOVE, self::RELATION_PREVIOUS_CHILD, self::RELATION_NEIGHBOR_IS_PARENT,
                                self::RELATION_NEIGHBOR_IS_CHILD, self::RELATION_NEIGHBOR_IS_SIBLING]))
      $this->relationship = $relationship;
    else
      throw new ZigbeeException("Invalid value for Relationship");
    }

  /**
   * @return string
   * @throws ZigbeeException
   */
  public function displayRelationship()
    {
    switch($this->getRelationship())
      {
      case self::RELATION_NEIGHBOR_IS_CHILD:
        return "RELATION_NEIGHBOR_IS_CHILD";
      case self::RELATION_NEIGHBOR_IS_PARENT:
        return "RELATION_NEIGHBOR_IS_PARENT";
      case self::RELATION_NEIGHBOR_IS_SIBLING:
        return "RELATION_NEIGHBOR_IS_SIBLING";
      case self::RELATION_NONE_OF_THE_ABOVE:
        return "RELATION_NONE_OF_THE_ABOVE";
      case self::RELATION_PREVIOUS_CHILD:
        return "RELATION_PREVIOUS_CHILD";
      }

    throw new ZigbeeException("Invalid value for Relationship");
    }

  /**
   * Indicates if neighbor's receiver is
   * enabled during idle portions of the CAP:
   *
   * 0x0 = Receiver is off
   * 0x1 = Receiver is on
   * 0x2 = unknown
   *
   * @return int
   */
  public function getRxOnWhenIdle()
    {
    return $this->rx_on_when_idle;
    }

  /**
   * Indicates if neighbor's receiver is
   * enabled during idle portions of the CAP:
   *
   * 0x0 = Receiver is off
   * 0x1 = Receiver is on
   * 0x2 = unknown
   *
   * @param int $rx_on_when_idle
   * @throws ZigbeeException
   */
  public function setRxOnWhenIdle($rx_on_when_idle)
    {
    if(in_array($rx_on_when_idle, [self::RECEIVER_ON_WHEN_IDLE, self::RECEIVER_UNKNOWN, self::RECEIVER_OFF_WHEN_IDLE]))
      $this->rx_on_when_idle = $rx_on_when_idle;
    else
      throw new ZigbeeException("Invalid value for RxOnWhenIdle");
    }
  }