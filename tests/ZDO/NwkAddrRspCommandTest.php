<?php

namespace Munisense\Zigbee\ZDO;


use Munisense\Zigbee\Exception\MuniZigbeeException;

class NwkAddrRspCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testSetStatus()
    {
    $frame = new NwkAddrRspCommand();
    $frame->setStatus(Status::DEVICE_NOT_FOUND);
    $this->assertEquals(Status::DEVICE_NOT_FOUND, $frame->getStatus());
    }

  /**
   * @throws \Munisense\Zigbee\Exception\MuniZigbeeException
   * @expectedException \Munisense\Zigbee\Exception\MuniZigbeeException
   */
  public function testSetStatus_InvalidInput()
    {
    $frame = new NwkAddrRspCommand();
    $frame->setStatus(Status::NO_ENTRY);
    }

  /**
   * If the RequestType in the request is Extended Response and there are no associated devices on the
   * Remote Device, this field shall be set to 0.
   *
   * If an error occurs or the RequestType in the request is for a Single
   * Device Response, this field shall not be included in the frame.
   */
  public function testGetFrameEmptyList()
    {
    $frame = NwkAddrRspCommand::constructSingle(Status::SUCCESS, 123456, 0xae);
    $base_str = "0x00 0x40 0xe2 0x01 0x00 0x00 0x00 0x00 0x00 0xae";
    $this->assertEquals($base_str, $frame->displayFrame());

    // Num associated devices as 0x00 and omit the rest
    $frame = NwkAddrRspCommand::constructExtended(Status::SUCCESS, 123456, 0xae, 0x01, []);
    $this->assertEquals($base_str." 0x00", $frame->displayFrame());
    }

  public function testGetFrameExtended()
    {
    $frame = NwkAddrRspCommand::constructExtended(Status::SUCCESS, 123456, 0xae, 0x01, [0x1234, 0xabcd]);
    $base_str = "0x00 0x40 0xe2 0x01 0x00 0x00 0x00 0x00 0x00 0xae";
    $this->assertEquals($base_str." 0x02 0x01 0x34 0x12 0xcd 0xab", $frame->displayFrame());
    }

  public function testSetFrameSimple()
    {
    $base_frame = NwkAddrRspCommand::constructSingle(Status::SUCCESS, 123456, 0xae);
    $frame = new NwkAddrRspCommand($base_frame->getFrame());
    $this->assertEquals($base_frame->displayFrame(), $frame->displayFrame());
    }

  public function testSetFrameExtended()
    {
    $base_frame = NwkAddrRspCommand::constructExtended(Status::SUCCESS, 123456, 0xae, 0x01, [0x1234, 0xabcd]);
    $frame = new NwkAddrRspCommand($base_frame->getFrame());
    $this->assertEquals($base_frame->displayFrame(), $frame->displayFrame());
    }

  /**
   * @expectedException MuniZigbeeException
   */
  public function addInvalidAssociatedDevice()
    {
    $frame = new NwkAddrRspCommand();
    $frame->setAssociatedDeviceList([0xff00, 0xffff+1]);
    }

  public function testInclusionByConstructor()
    {
    $base_frame = NwkAddrRspCommand::constructExtended(Status::SUCCESS, 123456, 0xae, 0x01, [0x1234, 0xabcd]);
    $transaction_id = chr(0x12);
    $parent = new ZDOFrame($transaction_id .$base_frame->getFrame(), NwkAddrRspCommand::CLUSTER_ID);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDO\\NwkAddrRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }

  public function testInclusionByStaticConstructor()
    {
    $base_frame = NwkAddrRspCommand::constructExtended(Status::SUCCESS, 123456, 0xae, 0x01, [0x1234, 0xabcd]);
    $transaction_id = 20;
    $parent = ZDOFrame::construct($base_frame, $transaction_id);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDO\\NwkAddrRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }
  }
 