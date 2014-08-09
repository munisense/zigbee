<?php

namespace Munisense\Zigbee\ZDP\Network;

class NeighborDescriptorTest extends \PHPUnit_Framework_TestCase
  {
  public function testConstruct()
    {
    $neighbor_descriptor = NeighborDescriptor::construct(
      "3781220488658316", "3781220489559882", 0x0a, NeighborDescriptor::ZIGBEE_END_DEVICE, NeighborDescriptor::RECEIVER_ON_WHEN_IDLE,
      NeighborDescriptor::RELATION_NEIGHBOR_IS_SIBLING, NeighborDescriptor::NEIGHBOR_ACCEPTS_JOIN_REQUESTS_UNKNOWN, 0x0f, 0xf0
    );

    $this->assertEquals("0x8c 0x3d 0x0b 0x00 0x00 0x6f 0x0d 0x00 0x4a 0xff 0x18 0x00 0x00 0x6f 0x0d 0x00 0x0a 0x00 0x46 0x02 0x0f 0xf0",  $neighbor_descriptor->displayFrame());
    }

  public function testReverse()
    {
    $neighbor_descriptor = NeighborDescriptor::construct(
      "3781220488658316", "3781220489559882", 0x0a, NeighborDescriptor::ZIGBEE_END_DEVICE, NeighborDescriptor::RECEIVER_ON_WHEN_IDLE,
      NeighborDescriptor::RELATION_NEIGHBOR_IS_SIBLING, NeighborDescriptor::NEIGHBOR_ACCEPTS_JOIN_REQUESTS_UNKNOWN, 0x0f, 0xf0
    );

    $frame = $neighbor_descriptor->getFrame();
    $new_descriptor = new NeighborDescriptor($frame);

    $this->assertEquals($neighbor_descriptor->displayFrame(), $new_descriptor->displayFrame());
    }
  }
 