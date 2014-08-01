<?php

namespace Munisense\Zigbee\ZDP\Discovery;
use Munisense\Zigbee\ZDP\Status;
use Munisense\Zigbee\ZDP\ZDPFrame;

/**
 * Class ActiveEPRspCommandTest
 *
 * @package Munisense\Zigbee\ZDP\Discovery
 */
class ActiveEPRspCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testStaticConstructFailure()
    {
    $frame = ActiveEPRspCommand::constructFailure(Status::DEVICE_NOT_FOUND, 0xab12);
    $this->assertEquals("0x81 0x12 0xab 0x00", $frame->displayFrame());
    }

  public function testStaticConstructSuccess()
    {
    $frame = ActiveEPRspCommand::constructSuccess(0xab12, [0x00, 0x0a]);
    $this->assertEquals("0x00 0x12 0xab 0x02 0x00 0x0a", $frame->displayFrame());
    }

  /**
   * @expectedException \Munisense\Zigbee\Exception\MuniZigbeeException
   */
  public function testInvalidEP()
    {
    $frame = new ActiveEPRspCommand();
    $frame->setActiveEpList([0xff+1]);
    }

  public function testInclusionByConstructor()
    {
    $base_frame = ActiveEPRspCommand::constructSuccess(0xab12, [0x00, 0x0a]);
    $transaction_id = chr(0x12);
    $parent = new ZDPFrame($transaction_id .$base_frame->getFrame(), $base_frame->getClusterId());
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\ActiveEPRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }

  public function testInclusionByStaticConstructor()
    {
    $base_frame = ActiveEPRspCommand::constructSuccess(0xab12, [0x00, 0x0a]);
    $transaction_id = 20;
    $parent = ZDPFrame::construct($base_frame, $transaction_id);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\ActiveEPRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }
  }
 