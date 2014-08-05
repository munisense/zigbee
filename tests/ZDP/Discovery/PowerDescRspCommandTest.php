<?php

namespace Munisense\Zigbee\ZDP\Discovery;
use Munisense\Zigbee\ZDP\Status;
use Munisense\Zigbee\ZDP\ZDPFrame;

/**
 * Class PowerDescRspCommandTest
 *
 * @package Munisense\Zigbee\ZDP\Discovery
 */
class PowerDescRspCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testStaticConstructFailure()
    {
    $frame = PowerDescRspCommand::constructFailure(Status::DEVICE_NOT_FOUND, 0xab12);
    $this->assertEquals("0x81 0x12 0xab", $frame->displayFrame());
    }

  public function testStaticConstructSuccess()
    {
    $power_descriptor = new NodePowerDescriptor();
    $frame = PowerDescRspCommand::constructSuccess(0xab12, $power_descriptor);
    $this->assertEquals(trim("0x00 0x12 0xab ".$power_descriptor->displayFrame()), $frame->displayFrame());
    }

  public function testInclusionByConstructor()
    {
    $base_frame = PowerDescRspCommand::constructFailure(Status::DEVICE_NOT_FOUND, 0xab12);
    $transaction_id = chr(0x12);
    $parent = new ZDPFrame($transaction_id .$base_frame->getFrame(), $base_frame->getClusterId());
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\PowerDescRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }

  public function testInclusionByStaticConstructor()
    {
    $base_frame = PowerDescRspCommand::constructFailure(Status::DEVICE_NOT_FOUND, 0xab12);
    $transaction_id = 20;
    $parent = ZDPFrame::construct($base_frame, $transaction_id);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\PowerDescRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }
  }
 