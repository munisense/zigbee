<?php

namespace Munisense\Zigbee\ZDP\Network;

class RoutingDescriptorTest extends \PHPUnit_Framework_TestCase
  {
  public function testConstruct()
    {
    $routing_descriptor = RoutingDescriptor::construct(0x1234, RoutingDescriptor::VALIDATION_UNDERWAY, 1, 0, 1, 0xabcd);
    $this->assertEquals("0x34 0x12 0x2c 0xcd 0xab",  $routing_descriptor->displayFrame());
    }

  public function testReverse()
    {
    $routing_descriptor = RoutingDescriptor::construct(0x1234, RoutingDescriptor::DISCOVERY_FAILED, 0, 1, 0, 0xabcd);
    $frame = $routing_descriptor->getFrame();
    $new_descriptor = new RoutingDescriptor($frame);

    $this->assertEquals($routing_descriptor->displayFrame(), $new_descriptor->displayFrame());
    }
  }
 