<?php

namespace Munisense\Zigbee\ZDP\Network;
use Munisense\Zigbee\ZDP\Status;
use Munisense\Zigbee\ZDP\ZDPFrame;

/**
 * Class MgmtRtgRspCommandTest
 *
 * @package Munisense\Zigbee\ZDP\Network
 */
class MgmtRtgRspCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testConstructSuccess()
    {
    $routing_descriptor_1 = RoutingDescriptor::construct(0x1234, RoutingDescriptor::VALIDATION_UNDERWAY, 1, 0, 1, 0xabcd);
    $routing_descriptor_2 = RoutingDescriptor::construct(0x2347, RoutingDescriptor::ACTIVE, 1, 1, 0, 0xabcd);

    $frame = MgmtRtgRspCommand::constructSuccess(0x0a, 0x12, [
      $routing_descriptor_1,
      $routing_descriptor_2
    ]);

    $this->assertEquals("0x00 0x0a 0x12 0x02 ".$routing_descriptor_1->displayFrame()." ".$routing_descriptor_2->displayFrame(), $frame->displayFrame());
    }

  public function testConstructFailure()
    {
    $frame = MgmtRtgRspCommand::constructFailure(Status::NO_DESCRIPTOR);
    $this->assertEquals("0x89", $frame->displayFrame());
    }


  public function testInclusionByConstructor()
    {
    $base_frame = MgmtRtgRspCommand::constructSuccess(80, 10, [
      RoutingDescriptor::construct(0x1234, RoutingDescriptor::VALIDATION_UNDERWAY, 1, 0, 1, 0xabcd),
      RoutingDescriptor::construct(0x2347, RoutingDescriptor::ACTIVE, 1, 1, 0, 0xabcd)
    ]);

    $transaction_id = chr(0x12);
    $parent = new ZDPFrame($transaction_id .$base_frame->getFrame(), $base_frame->getClusterId());
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Network\\MgmtRtgRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }

  public function testInclusionByStaticConstructor()
    {
    $base_frame = MgmtRtgRspCommand::constructSuccess(80, 10, [
      RoutingDescriptor::construct(0x1234, RoutingDescriptor::VALIDATION_UNDERWAY, 1, 0, 1, 0xabcd),
      RoutingDescriptor::construct(0x2347, RoutingDescriptor::ACTIVE, 1, 1, 0, 0xabcd)
    ]);

    $transaction_id = 20;
    $parent = ZDPFrame::construct($base_frame, $transaction_id);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Network\\MgmtRtgRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }
  }
 