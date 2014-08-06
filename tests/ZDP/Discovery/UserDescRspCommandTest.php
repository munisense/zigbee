<?php

namespace Munisense\Zigbee\ZDP\Discovery;
use Munisense\Zigbee\ZDP\Status;
use Munisense\Zigbee\ZDP\ZDPFrame;

/**
 * Class UserDescRspCommandTest
 *
 * @package Munisense\Zigbee\ZDP\Discovery
 */
class UserDescRspCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testStaticConstructFailure()
    {
    $frame = UserDescRspCommand::constructFailure(Status::DEVICE_NOT_FOUND, 0xab12);
    $this->assertEquals("0x81 0x12 0xab 0x00", $frame->displayFrame());
    }

  public function testStaticConstructSuccess()
    {
    $frame = UserDescRspCommand::constructSuccess(0xab12, UserDescriptor::construct("test"));
    $this->assertEquals("0x00 0x12 0xab 0x04 0x74 0x65 0x73 0x74", $frame->displayFrame());
    }

  public function testReverse()
    {
    $user_desc = UserDescRspCommand::constructSuccess(0xab12, UserDescriptor::construct("hello"));
    $frame = $user_desc->getFrame();
    $new_descriptor = new UserDescRspCommand($frame);
    $this->assertEquals($user_desc->displayFrame(), $new_descriptor->displayFrame());
    }

  public function testInclusionByConstructor()
    {
    $base_frame = UserDescRspCommand::constructFailure(Status::DEVICE_NOT_FOUND, 0xab12);
    $transaction_id = chr(0x12);
    $parent = new ZDPFrame($transaction_id .$base_frame->getFrame(), $base_frame->getClusterId());
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\UserDescRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }

  public function testInclusionByStaticConstructor()
    {
    $base_frame = UserDescRspCommand::constructFailure(Status::DEVICE_NOT_FOUND, 0xab12);
    $transaction_id = 20;
    $parent = ZDPFrame::construct($base_frame, $transaction_id);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\UserDescRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }
  }
 