<?php

namespace Munisense\Zigbee\ZDP\Discovery;
use Munisense\Zigbee\ZDP\Status;
use Munisense\Zigbee\ZDP\ZDPFrame;

/**
 * Class SimpleDescRspCommandTest
 *
 * @package Munisense\Zigbee\ZDP\Discovery
 */
class SimpleDescRspCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testStaticConstructFailure()
    {
    $frame = SimpleDescRspCommand::constructFailure(Status::DEVICE_NOT_FOUND, 0xab12);
    $this->assertEquals("0x81 0x12 0xab 0x00", $frame->displayFrame());
    }

  public function testStaticConstructSuccess()
    {
    $frame = SimpleDescRspCommand::constructSuccess(0xab12, SimpleDescriptor::construct(0x0a, 0x1234, 0xabcd, 8));
    $this->assertEquals("0x00 0x12 0xab 0x08 0x0a 0x34 0x12 0xcd 0xab 0x08 0x00 0x00", $frame->displayFrame());
    }

  public function testReverse()
    {
    $simple_desc = SimpleDescRspCommand::constructSuccess(0xab12, SimpleDescriptor::construct(0x0a, 0x1234, 0xabcd, 8));
    $frame = $simple_desc->getFrame();
    $new_descriptor = new SimpleDescRspCommand($frame);
    $this->assertEquals($simple_desc->displayFrame(), $new_descriptor->displayFrame());
    }

  public function testInclusionByConstructor()
    {
    $base_frame = SimpleDescRspCommand::constructFailure(Status::DEVICE_NOT_FOUND, 0xab12);
    $transaction_id = chr(0x12);
    $parent = new ZDPFrame($transaction_id .$base_frame->getFrame(), $base_frame->getClusterId());
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\SimpleDescRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }

  public function testInclusionByStaticConstructor()
    {
    $base_frame = SimpleDescRspCommand::constructFailure(Status::DEVICE_NOT_FOUND, 0xab12);
    $transaction_id = 20;
    $parent = ZDPFrame::construct($base_frame, $transaction_id);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\SimpleDescRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }
  }
 