<?php

namespace Munisense\Zigbee\ZDP\Discovery;
use Munisense\Zigbee\ZDP\Status;
use Munisense\Zigbee\ZDP\ZDPFrame;

/**
 * Class UserDescConfCommandTest
 *
 * @package Munisense\Zigbee\ZDP\Discovery
 */
class UserDescConfCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testStaticConstruct()
    {
    $frame = UserDescConfCommand::construct(Status::DEVICE_NOT_FOUND, 0xab12);
    $this->assertEquals("0x81 0x12 0xab", $frame->displayFrame());
    }

  public function testReverse()
    {
    $user_desc = UserDescConfCommand::construct(Status::SUCCESS, 0xab12);
    $frame = $user_desc->getFrame();
    $new_descriptor = new UserDescConfCommand($frame);
    $this->assertEquals($user_desc->displayFrame(), $new_descriptor->displayFrame());
    }

  public function testInclusionByConstructor()
    {
    $base_frame = UserDescConfCommand::construct(Status::SUCCESS, 0xab12);
    $transaction_id = chr(0x12);
    $parent = new ZDPFrame($transaction_id .$base_frame->getFrame(), $base_frame->getClusterId());
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\UserDescConfCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }

  public function testInclusionByStaticConstructor()
    {
    $base_frame = UserDescConfCommand::construct(Status::SUCCESS, 0xab12);
    $transaction_id = 20;
    $parent = ZDPFrame::construct($base_frame, $transaction_id);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\UserDescConfCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }
  }
 