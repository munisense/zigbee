<?php

namespace ZDP\Discovery;

use Munisense\Zigbee\ZDP\Discovery\SimpleDescriptor;

class SimpleDescriptorTest extends \PHPUnit_Framework_TestCase
  {
  public function testGetFrame()
    {
    $simple_desc = SimpleDescriptor::construct(0x0a, 0x1234, 0xabcd, 0b00001010, [0x1357], [0x0011,0x0022]);
    $this->assertEquals("0x0a 0x34 0x12 0xcd 0xab 0x0a 0x01 0x57 0x13 0x02 0x11 0x00 0x22 0x00", $simple_desc->displayFrame());
    }

  public function testSetFrame()
    {
    $base_frame = SimpleDescriptor::construct(0x0a, 0x1234, 0xabcd, 0b00001010, [0x1357], [0x0011,0x0022]);
    $frame = new SimpleDescriptor($base_frame->getFrame());
    $this->assertEquals($base_frame->displayFrame(), $frame->displayFrame());
    }
  }
 