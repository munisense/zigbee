<?php

namespace Munisense\Zigbee\ZDO\Discovery;
use Munisense\Zigbee\ZDO\ZDOFrame;

/**
 * Class NodeDescReqCommandTest
 *
 * @package Munisense\Zigbee\ZDO\Discovery
 */
class NodeDescReqCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testStaticConstruct()
    {
    $frame = NodeDescReqCommand::construct(0x1234);
    $this->assertEquals("0x34 0x12", $frame->displayFrame());
    }

  public function testConstructor()
    {
    $frame = new NodeDescReqCommand(chr(0x12).chr(0x34));
    $this->assertEquals("0x12 0x34", $frame->displayFrame());
    }

  public function testInclusionByConstructor()
    {
    $base_frame = NodeDescReqCommand::construct(0x77ae);
    $transaction_id = chr(0x12);
    $parent = new ZDOFrame($transaction_id .$base_frame->getFrame(), $base_frame->getClusterId());
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDO\\Discovery\\NodeDescReqCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }

  public function testInclusionByStaticConstructor()
    {
    $base_frame = NodeDescReqCommand::construct(0x77ae);
    $transaction_id = 20;
    $parent = ZDOFrame::construct($base_frame, $transaction_id);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDO\\Discovery\\NodeDescReqCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }
  }
 