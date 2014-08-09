<?php

namespace Munisense\Zigbee\ZDP\Network;
use Munisense\Zigbee\ZDP\ZDPFrame;

/**
 * Class MgmtCacheReqCommandTest
 *
 * @package Munisense\Zigbee\ZDP\Network
 */
class MgmtCacheReqCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testInclusionByConstructor()
    {
    $base_frame = MgmtCacheReqCommand::construct(0xa1);
    $transaction_id = chr(0x12);
    $parent = new ZDPFrame($transaction_id .$base_frame->getFrame(), $base_frame->getClusterId());
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Network\\MgmtCacheReqCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }

  public function testInclusionByStaticConstructor()
    {
    $base_frame = MgmtCacheReqCommand::construct(0xa1);
    $transaction_id = 20;
    $parent = ZDPFrame::construct($base_frame, $transaction_id);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Network\\MgmtCacheReqCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }
  }
 