<?php

namespace Munisense\Zigbee\ZDP\Discovery;


use Munisense\Zigbee\ZDP\Status;
use Munisense\Zigbee\ZDP\ZDPFrame;

class NodeDescRspCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testStaticConstructFailure()
    {
    $frame = NodeDescRspCommand::constructFailure(Status::DEVICE_NOT_FOUND, 0xab12);
    $this->assertEquals("0x81 0x12 0xab 0x00", $frame->displayFrame());
    }

  public function testStaticConstructSuccess()
    {
    $node_descriptor = new NodeDescriptor();
    $frame = NodeDescRspCommand::constructSuccess(0xab12, $node_descriptor);
    $this->assertEquals(trim("0x00 0x12 0xab 0x00 ".$node_descriptor->getFrame()), $frame->displayFrame());
    }

  public function testInclusionByConstructor()
    {
    $base_frame = NodeDescRspCommand::constructFailure(Status::DEVICE_NOT_FOUND, 0xab12);
    $transaction_id = chr(0x12);
    $parent = new ZDPFrame($transaction_id .$base_frame->getFrame(), $base_frame->getClusterId());
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\NodeDescRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }

  public function testInclusionByStaticConstructor()
    {
    $base_frame = NodeDescRspCommand::constructFailure(Status::DEVICE_NOT_FOUND, 0xab12);
    $transaction_id = 20;
    $parent = ZDPFrame::construct($base_frame, $transaction_id);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\NodeDescRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }
  }
 