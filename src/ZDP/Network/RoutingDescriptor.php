<?php

namespace Munisense\Zigbee\ZDP\Network;

use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\ZigbeeException;

class RoutingDescriptor extends AbstractFrame
  {
  private $destination_address;

  private $status;
  const ACTIVE  = 0x00;
  const DISCOVERY_UNDERWAY = 0x01;
  const DISCOVERY_FAILED = 0x02;
  const INACTIVE = 0x03;
  const VALIDATION_UNDERWAY = 0x04;

  private $memory_constrained;
  private $many_to_one;
  private $route_record_required;

  private $next_hop_address;

  public static function construct($network_address, $status, $memory_constrained, $many_to_one,
                                   $route_record_required, $next_hop_address)
    {
    $frame = new self;
    $frame->setDestinationAddress($network_address);
    $frame->setStatus($status);
    $frame->setMemoryConstrained($memory_constrained);
    $frame->setManyToOne($many_to_one);
    $frame->setRouteRecordRequired($route_record_required);
    $frame->setNextHopAddress($next_hop_address);
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

    Buffer::packInt16u($frame, $this->getDestinationAddress());

    $byte1 = $this->getStatus() & 7;
    $byte1 |= ($this->getMemoryConstrained() << 3) & 8;
    $byte1 |= ($this->getManyToOne() << 4) & 16;
    $byte1 |= ($this->getRouteRecordRequired() << 5) & 32;
    Buffer::packInt8u($frame, $byte1);

    Buffer::packInt16u($frame, $this->getNextHopAddress());

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
    $this->setDestinationAddress(Buffer::unpackInt16u($frame));

    $byte1 = Buffer::unpackInt8u($frame);
    $this->setStatus(($byte1 & 7) >> 0);
    $this->setMemoryConstrained(($byte1 & 8) >> 3);
    $this->setManyToOne(($byte1 & 16) >> 4);
    $this->setRouteRecordRequired(($byte1 & 32) >> 5);

    $this->setNextHopAddress(Buffer::unpackInt16u($frame));
    }

  /**
   * The 16-bit network address of the next hop on the way to the destination.
   *
   * @return int
   */
  public function getNextHopAddress()
    {
    return $this->next_hop_address;
    }

  /**
   * The 16-bit network address of the next hop on the way to the destination.
   *
   * @param int $next_hop_address
   *
   * @throws ZigbeeException
   */
  public function setNextHopAddress($next_hop_address)
    {
    if($next_hop_address >= 0x0000 && $next_hop_address <= 0xffff)
      $this->next_hop_address = $next_hop_address;
    else
      throw new ZigbeeException("Depth must be in range of 0x0000 - 0xffff: " . sprintf("0x%04x", $next_hop_address));
    }

  public function displayNextHopAddress()
    {
    return sprintf("0x%04x", $this->getNextHopAddress());
    }

  /**
   * The status of the route.
   *
   * 0x0 = ACTIVE.
   * 0x1 = DISCOVERY_UNDERWAY.
   * 0x2 = DISCOVERY_FAILED.
   * 0x3 = INACTIVE.
   * 0x4 = VALIDATION_UNDERWAY
   *
   * @return int
   */
  public function getStatus()
    {
    return $this->status;
    }

  /**
   * The status of the route.
   *
   * 0x0 = ACTIVE.
   * 0x1 = DISCOVERY_UNDERWAY.
   * 0x2 = DISCOVERY_FAILED.
   * 0x3 = INACTIVE.
   * 0x4 = VALIDATION_UNDERWAY
   *
   * @param int $status
   *
   * @throws ZigbeeException
   */
  public function setStatus($status)
    {
    if(in_array($status, [self::ACTIVE, self::DISCOVERY_FAILED, self::DISCOVERY_UNDERWAY, self::INACTIVE, self::VALIDATION_UNDERWAY]))
      $this->status = $status;
    else
      throw new ZigbeeException("Invalid Status supplied: ".$status);
    }

  /**
   * @return string
   */
  public function displayStatus()
    {
    switch($this->getStatus())
      {
      case self::ACTIVE: return "ACTIVE";
      case self::DISCOVERY_FAILED: return "DISCOVERY_FAILED";
      case self::DISCOVERY_UNDERWAY: return "DISCOVERY_UNDERWAY";
      case self::INACTIVE: return "INACTIVE";
      case self::VALIDATION_UNDERWAY: return "VALIDATION_UNDERWAY";
      default: return "RESERVED/UNKNOWN";
      }
    }

  /**
   * The 16-bit network address of this route.
   *
   * @param int $destination_address
   *
   * @throws ZigbeeException
   */
  public function setDestinationAddress($destination_address)
    {
    if($destination_address >= 0x0000 && $destination_address <= 0xffff)
      $this->destination_address = $destination_address;
    else
      throw new ZigbeeException("Destination Address not in range 0x0000 - 0xffff: ".sprintf("0x%04x", $destination_address));
    }

  /**
   * The 16-bit network address of the route.
   *
   * @return int
   */
  public function getDestinationAddress()
    {
    return $this->destination_address;
    }

  /**
   * @return string Hexadecimal representation of Destination Address
   */
  public function displayDestinationAddress()
    {
    return sprintf("0x%04x", $this->getDestinationAddress());
    }

  /**
   * A flag indicating that a route record command frame should be sent to the
   * destination prior to the next data packet.
   *
   * @return int
   */
  public function getRouteRecordRequired()
    {
    return $this->route_record_required;
    }

  /**
   * A flag indicating that a route record command frame should be sent to the
   * destination prior to the next data packet.
   *
   * @param int $route_record_required
   *
   * @throws ZigbeeException
   */
  public function setRouteRecordRequired($route_record_required)
    {
    if($route_record_required == 0 || $route_record_required == 1)
      $this->route_record_required = $route_record_required;
    else
      throw new ZigbeeException("Invalid Route Record Required");
    }

  public function displayRouteRecordRequired()
    {
    return $this->getRouteRecordRequired() ? "Y" : "N";
    }

  /**
   * A flag indicating that the destination is a concentrator that issued a manyto-one request.
   *
   * @return int
   */
  public function getManyToOne()
    {
    return $this->many_to_one;
    }

  /**
   * A flag indicating that the destination is a concentrator that issued a manyto-one request.
   *
   * @param int $many_to_one
   *
   * @throws ZigbeeException
   */
  public function setManyToOne($many_to_one)
    {
    if($many_to_one == 0 || $many_to_one == 1)
      $this->many_to_one = $many_to_one;
    else
      throw new ZigbeeException("Invalid Many To One");
    }

  /**
   * @return string
   * @throws ZigbeeException
   */
  public function displayManyToOne()
    {
    return $this->getManyToOne() ? "Y" : "N";
    }

  /**
   * A flag indicating whether the device is a memory constrained concentrator
   *
   * @return int
   */
  public function getMemoryConstrained()
    {
    return $this->memory_constrained;
    }

  /**
   * A flag indicating whether the device is a memory constrained concentrator
   *
   * @param int $memory_constrained
   *
   * @throws ZigbeeException
   */
  public function setMemoryConstrained($memory_constrained)
    {
    if($memory_constrained == 0 || $memory_constrained == 1)
      $this->memory_constrained = $memory_constrained;
    else
      throw new ZigbeeException("Invalid value for Memory Constrained");
    }
  }