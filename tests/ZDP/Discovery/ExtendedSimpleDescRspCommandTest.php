<?php

namespace Munisense\Zigbee\ZDP\Discovery;

use Munisense\Zigbee\ZDP\Status;
use Munisense\Zigbee\ZDP\ZDPFrame;

class ExtendedSimpleDescRspCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testSetStatus()
    {
    $frame = new ExtendedSimpleDescRspCommand();
    $frame->setStatus(Status::DEVICE_NOT_FOUND);
    $this->assertEquals(Status::DEVICE_NOT_FOUND, $frame->getStatus());
    }

  /**
   * @throws \Munisense\Zigbee\Exception\ZigbeeException
   * @expectedException \Munisense\Zigbee\Exception\ZigbeeException
   */
  public function testSetStatus_InvalidInput()
    {
    $frame = new ExtendedSimpleDescRspCommand();
    $frame->setStatus(Status::NO_ENTRY);
    }

  public function testConstructFailure()
    {
    $frame = ExtendedSimpleDescRspCommand::constructFailure(Status::DEVICE_NOT_FOUND, 0x77ae, 0x0a, 0x12, 0xab, 0xef);
    $this->assertEquals("0x81 0xae 0x77 0x0a 0x12 0xab 0xef", $frame->displayFrame());
    }

  public function testConstructSuccess()
    {
    $frame = ExtendedSimpleDescRspCommand::constructSuccess(0x77ae, 0x0a, 0x12, 0xab, 0xef, [0x1234, 0xabcd]);
    $base_str = "0x00 0xae 0x77 0x0a 0x12 0xab 0xef";
    $this->assertEquals($base_str." 0x34 0x12 0xcd 0xab", $frame->displayFrame());
    }

  public function testSetFrameFailure()
    {
    $base_frame = ExtendedSimpleDescRspCommand::constructFailure(Status::DEVICE_NOT_FOUND, 0x77ae, 0x0a, 0x12, 0xab, 0xef);
    $frame = new ExtendedSimpleDescRspCommand($base_frame->getFrame());
    $this->assertEquals($base_frame->displayFrame(), $frame->displayFrame());
    }

  public function testSetFrameSuccess()
    {
    $base_frame = ExtendedSimpleDescRspCommand::constructSuccess(0x77ae, 0x0a, 0x12, 0xab, 0xef, [0x1234, 0xabcd]);
    $frame = new ExtendedSimpleDescRspCommand($base_frame->getFrame());
    $this->assertEquals($base_frame->displayFrame(), $frame->displayFrame());
    }

  /**
   * @expectedException \Munisense\Zigbee\Exception\ZigbeeException
   */
  public function testAddInvalidCluster()
    {
    $frame = new ExtendedSimpleDescRspCommand();
    $frame->setAppClusterList([0xff00, 0xffff+1]);
    }

  public function testInclusionByConstructor()
    {
    $base_frame = ExtendedSimpleDescRspCommand::constructSuccess(0x77ae, 0x0a, 0x12, 0xab, 0xef, [0x1234, 0xabcd]);
    $transaction_id = chr(0x12);
    $parent = new ZDPFrame($transaction_id .$base_frame->getFrame(), $base_frame->getClusterId());
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\ExtendedSimpleDescRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }

  public function testInclusionByStaticConstructor()
    {
    $base_frame = ExtendedSimpleDescRspCommand::constructSuccess(0x77ae, 0x0a, 0x12, 0xab, 0xef, [0x1234, 0xabcd]);
    $transaction_id = 20;
    $parent = ZDPFrame::construct($base_frame, $transaction_id);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\ExtendedSimpleDescRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }
  }
 