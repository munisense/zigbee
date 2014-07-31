<?php

namespace Munisense\Zigbee\ZDO\Discovery;
use Munisense\Zigbee\ZDO\Status;
use Munisense\Zigbee\ZDO\ZDOFrame;

/**
 * Class SimpleDescRspCommandTest
 *
 * @package Munisense\Zigbee\ZDO\Discovery
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

  public function testInclusionByConstructor()
    {
    $base_frame = SimpleDescRspCommand::constructFailure(Status::DEVICE_NOT_FOUND, 0xab12);
    $transaction_id = chr(0x12);
    $parent = new ZDOFrame($transaction_id .$base_frame->getFrame(), $base_frame->getClusterId());
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDO\\Discovery\\SimpleDescRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }

  public function testInclusionByStaticConstructor()
    {
    $base_frame = SimpleDescRspCommand::constructFailure(Status::DEVICE_NOT_FOUND, 0xab12);
    $transaction_id = 20;
    $parent = ZDOFrame::construct($base_frame, $transaction_id);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDO\\Discovery\\SimpleDescRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }
  }
 