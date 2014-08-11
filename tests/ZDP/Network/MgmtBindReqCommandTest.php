<?php

namespace Munisense\Zigbee\ZDP\Network;
use Munisense\Zigbee\ZDP\ZDPFrame;

/**
 * Class MgmtBindReqCommandTest
 *
 * @package Munisense\Zigbee\ZDP\Network
 */
class MgmtBindReqCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testInclusionByConstructor()
    {
    $base_frame = MgmtBindReqCommand::construct(0xa1);
    $transaction_id = chr(0x12);
    $parent = new ZDPFrame($transaction_id .$base_frame->getFrame(), $base_frame->getClusterId());
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Network\\MgmtBindReqCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }

  public function testInclusionByStaticConstructor()
    {
    $base_frame = MgmtBindReqCommand::construct(0xa1);
    $transaction_id = 20;
    $parent = ZDPFrame::construct($base_frame, $transaction_id);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Network\\MgmtBindReqCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }
  }
 