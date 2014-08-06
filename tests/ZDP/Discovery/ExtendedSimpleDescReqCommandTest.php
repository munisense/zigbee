<?php

namespace Munisense\Zigbee\ZDP\Discovery;
use Munisense\Zigbee\ZDP\ZDPFrame;

/**
 * Class ExtendedSimpleDescReqCommandTest
 *
 * @package Munisense\Zigbee\ZDP\Discovery
 */
class ExtendedSimpleDescReqCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testStaticConstruct()
    {
    $frame = ExtendedSimpleDescReqCommand::constructExtended(0x1234, 0xab, 0x0a);
    $this->assertEquals("0x34 0x12 0xab 0x0a", $frame->displayFrame());
    }

  public function testConstructor()
    {
    $frame = new ExtendedSimpleDescReqCommand(chr(0x12).chr(0x34).chr(0xab).chr(0x0a));
    $this->assertEquals("0x12 0x34 0xab 0x0a", $frame->displayFrame());
    }

  public function testInclusionByConstructor()
    {
    $base_frame = ExtendedSimpleDescReqCommand::constructExtended(0x77ae, 0xab, 0x0a);
    $transaction_id = chr(0x12);
    $parent = new ZDPFrame($transaction_id .$base_frame->getFrame(), $base_frame->getClusterId());
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\ExtendedSimpleDescReqCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }

  public function testInclusionByStaticConstructor()
    {
    $base_frame = ExtendedSimpleDescReqCommand::constructExtended(0x77ae, 0xab, 0x0a);
    $transaction_id = 20;
    $parent = ZDPFrame::construct($base_frame, $transaction_id);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\ExtendedSimpleDescReqCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }
  }
 