<?php

namespace Munisense\Zigbee\ZDO;

/**
 * Class IEEEAddrRspCommandTest
 *
 * @package Munisense\Zigbee\ZDO
 *
 * Most of the testing is already done in NwkAddrRspCommandTest
 */
class IEEEAddrRspCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testInclusionByConstructor()
    {
    $base_frame = IEEEAddrRspCommand::constructExtended(Status::SUCCESS, 123456, 0x77ae, 0x01, [0x1234, 0xabcd]);
    $transaction_id = chr(0x12);
    $parent = new ZDOFrame($transaction_id .$base_frame->getFrame(), $base_frame->getClusterId());
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDO\\IEEEAddrRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }

  public function testInclusionByStaticConstructor()
    {
    $base_frame = IEEEAddrRspCommand::constructExtended(Status::SUCCESS, 123456, 0x77ae, 0x01, [0x1234, 0xabcd]);
    $transaction_id = 20;
    $parent = ZDOFrame::construct($base_frame, $transaction_id);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDO\\IEEEAddrRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }
  }
 