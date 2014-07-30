<?php

namespace Munisense\Zigbee\ZDO;

class NwkAddrReqCommandTest extends \PHPUnit_Framework_TestCase
  {
  /**
   * @throws \Munisense\Zigbee\Exception\MuniZigbeeException
   * @expectedException \Munisense\Zigbee\Exception\MuniZigbeeException
   */
  public function testSetIEEEAddress_InvalidInput()
    {
    $frame = new NwkAddrReqCommand();
    $frame->setIeeeAddress(0xffffffffffffffffff);
    }

  public function testGetFrameSimple()
    {
    $frame = NwkAddrReqCommand::constructSingle(0xff);
    $this->assertEquals("0xff 0x00 0x00 0x00 0x00 0x00 0x00 0x00 0x00", $frame->displayFrame());
    }

  public function testGetFrameExtended()
    {
    $frame = NwkAddrReqCommand::constructExtended(0xff, 0x01);
    $this->assertEquals("0xff 0x00 0x00 0x00 0x00 0x00 0x00 0x00 0x01 0x01", $frame->displayFrame());
    }

  public function testSetFrameSimple()
    {
    $base_frame = NwkAddrReqCommand::constructSingle(0xff);
    $frame = new NwkAddrReqCommand($base_frame->getFrame());
    $this->assertEquals($base_frame->displayFrame(), $frame->displayFrame());
    }

  public function testSetFrameExtended()
    {
    $base_frame = NwkAddrReqCommand::constructExtended(0xff, 0x01);
    $frame = new NwkAddrReqCommand($base_frame->getFrame());
    $this->assertEquals($base_frame->displayFrame(), $frame->displayFrame());
    }

  public function testInclusionByConstructor()
    {
    $base_frame = NwkAddrReqCommand::constructExtended(0xff, 0x01);
    $transaction_id = chr(0x12);
    $parent = new ZDOFrame($transaction_id .$base_frame->getFrame(), $base_frame->getClusterId());
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDO\\NwkAddrReqCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }

  public function testInclusionByStaticConstructor()
    {
    $base_frame = NwkAddrReqCommand::constructExtended(0xff, 0x01);
    $transaction_id = 20;
    $parent = ZDOFrame::construct($base_frame, $transaction_id);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDO\\NwkAddrReqCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }
  }
 