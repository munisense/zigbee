<?php

namespace Munisense\Zigbee\ZDP\Discovery;

use Munisense\Zigbee\ZDP\ZDPFrame;

class IEEEAddrReqCommandTest extends \PHPUnit_Framework_TestCase
  {
  /**
   * @throws \Munisense\Zigbee\Exception\ZigbeeException
   * @expectedException \Munisense\Zigbee\Exception\ZigbeeException
   */
  public function testSetNwkAddress_InvalidInput()
    {
    $frame = new IEEEAddrReqCommand();
    $frame->setNwkAddress(0xffffff);
    }

  public function testGetFrameSimple()
    {
    $frame = IEEEAddrReqCommand::constructSingle(0x1234);
    $this->assertEquals("0x34 0x12 0x00 0x00", $frame->displayFrame());
    }

  public function testGetFrameExtended()
    {
    $frame = IEEEAddrReqCommand::constructExtended(0x1234, 0x01);
    $this->assertEquals("0x34 0x12 0x01 0x01", $frame->displayFrame());
    }

  public function testSetFrameSimple()
    {
    $base_frame = IEEEAddrReqCommand::constructSingle(0x1234);
    $frame = new IEEEAddrReqCommand($base_frame->getFrame());
    $this->assertEquals($base_frame->displayFrame(), $frame->displayFrame());
    }

  public function testSetFrameExtended()
    {
    $base_frame = IEEEAddrReqCommand::constructExtended(0x1234, 0x01);
    $frame = new IEEEAddrReqCommand($base_frame->getFrame());
    $this->assertEquals($base_frame->displayFrame(), $frame->displayFrame());
    }

  public function testInclusionByConstructor()
    {
    $base_frame = IEEEAddrReqCommand::constructExtended(0x1234, 0x01);
    $transaction_id = chr(0x12);
    $parent = new ZDPFrame($transaction_id .$base_frame->getFrame(), $base_frame->getClusterId());
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\IEEEAddrReqCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }

  public function testInclusionByStaticConstructor()
    {
    $base_frame = IEEEAddrReqCommand::constructExtended(0x1234, 0x01);
    $transaction_id = 20;
    $parent = ZDPFrame::construct($base_frame, $transaction_id);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\IEEEAddrReqCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }
  }
 