<?php

namespace Munisense\Zigbee\ZDP\Discovery;
use Munisense\Zigbee\ZDP\ZDPFrame;

/**
 * Class UserDescSetCommandTest
 *
 * @package Munisense\Zigbee\ZDP\Discovery
 */
class UserDescSetCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testStaticConstruct()
    {
    $frame = UserDescSetCommand::construct(0xab12, UserDescriptor::construct("test"));
    $this->assertEquals("0x12 0xab 0x04 0x74 0x65 0x73 0x74", $frame->displayFrame());
    }

  public function testReverse()
    {
    $user_desc = UserDescSetCommand::construct(0xab12, UserDescriptor::construct("hello"));
    $frame = $user_desc->getFrame();
    $new_descriptor = new UserDescSetCommand($frame);
    $this->assertEquals($user_desc->displayFrame(), $new_descriptor->displayFrame());
    }

  public function testInclusionByConstructor()
    {
    $base_frame = UserDescSetCommand::construct(0xab12, UserDescriptor::construct("hello"));
    $transaction_id = chr(0x12);
    $parent = new ZDPFrame($transaction_id .$base_frame->getFrame(), $base_frame->getClusterId());
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\UserDescSetCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }

  public function testInclusionByStaticConstructor()
    {
    $base_frame = UserDescSetCommand::construct(0xab12, UserDescriptor::construct("hello"));
    $transaction_id = 20;
    $parent = ZDPFrame::construct($base_frame, $transaction_id);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\UserDescSetCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }
  }