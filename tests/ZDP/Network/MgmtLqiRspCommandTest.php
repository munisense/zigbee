<?php

namespace Munisense\Zigbee\ZDP\Network;
use Munisense\Zigbee\ZDP\Status;
use Munisense\Zigbee\ZDP\ZDPFrame;

/**
 * Class MgmtLqiRspCommandTest
 *
 * @package Munisense\Zigbee\ZDP\Network
 */
class MgmtLqiRspCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testConstructSuccess()
    {
    $neighbor_descriptor_1 = NeighborDescriptor::construct(
      "3781220488658316", "3781220489559882", Status::SUCCESS, NeighborDescriptor::ZIGBEE_END_DEVICE, NeighborDescriptor::RECEIVER_ON_WHEN_IDLE,
      NeighborDescriptor::RELATION_NEIGHBOR_IS_SIBLING, NeighborDescriptor::NEIGHBOR_ACCEPTS_JOIN_REQUESTS_UNKNOWN, 0x0f, 0xf0
    );
    $neighbor_descriptor_2 = NeighborDescriptor::construct(
      "1231534523", "131412415445", Status::SUCCESS, NeighborDescriptor::ZIGBEE_COORDINATOR, NeighborDescriptor::RECEIVER_OFF_WHEN_IDLE,
      NeighborDescriptor::RELATION_NEIGHBOR_IS_CHILD, NeighborDescriptor::NEIGHBOR_IS_ACCEPTING_JOIN_REQUESTS, 0x1f, 0xf2
    );

    $frame = MgmtLqiRspCommand::constructSuccess(0x0a, 0x12, [
      $neighbor_descriptor_1,
      $neighbor_descriptor_2
    ]);

    $this->assertEquals("0x00 0x0a 0x12 0x02 ".$neighbor_descriptor_1->displayFrame()." ".$neighbor_descriptor_2->displayFrame(), $frame->displayFrame());
    }

  public function testConstructFailure()
    {
    $frame = MgmtLqiRspCommand::constructFailure(Status::NO_DESCRIPTOR);
    $this->assertEquals("0x89", $frame->displayFrame());
    }


  public function testInclusionByConstructor()
    {
    $base_frame = MgmtLqiRspCommand::constructSuccess(80, 10, [
      NeighborDescriptor::construct(
        "3781220488658316", "3781220489559882", Status::SUCCESS, NeighborDescriptor::ZIGBEE_END_DEVICE, NeighborDescriptor::RECEIVER_ON_WHEN_IDLE,
        NeighborDescriptor::RELATION_NEIGHBOR_IS_SIBLING, NeighborDescriptor::NEIGHBOR_ACCEPTS_JOIN_REQUESTS_UNKNOWN, 0x0f, 0xf0
      ),
      NeighborDescriptor::construct(
        "1231534523", "131412415445", Status::SUCCESS, NeighborDescriptor::ZIGBEE_COORDINATOR, NeighborDescriptor::RECEIVER_OFF_WHEN_IDLE,
        NeighborDescriptor::RELATION_NEIGHBOR_IS_CHILD, NeighborDescriptor::NEIGHBOR_IS_ACCEPTING_JOIN_REQUESTS, 0x1f, 0xf2
      )
    ]);

    $transaction_id = chr(0x12);
    $parent = new ZDPFrame($transaction_id .$base_frame->getFrame(), $base_frame->getClusterId());
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Network\\MgmtLqiRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }

  public function testInclusionByStaticConstructor()
    {
    $base_frame = MgmtLqiRspCommand::constructSuccess(80, 10, [
      NeighborDescriptor::construct(
        "3781220488658316", "3781220489559882", Status::SUCCESS, NeighborDescriptor::ZIGBEE_END_DEVICE, NeighborDescriptor::RECEIVER_ON_WHEN_IDLE,
        NeighborDescriptor::RELATION_NEIGHBOR_IS_SIBLING, NeighborDescriptor::NEIGHBOR_ACCEPTS_JOIN_REQUESTS_UNKNOWN, 0x0f, 0xf0
      ),
      NeighborDescriptor::construct(
        "1231534523", "131412415445", Status::SUCCESS, NeighborDescriptor::ZIGBEE_COORDINATOR, NeighborDescriptor::RECEIVER_OFF_WHEN_IDLE,
        NeighborDescriptor::RELATION_NEIGHBOR_IS_CHILD, NeighborDescriptor::NEIGHBOR_IS_ACCEPTING_JOIN_REQUESTS, 0x1f, 0xf2
      )
    ]);

    $transaction_id = 20;
    $parent = ZDPFrame::construct($base_frame, $transaction_id);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Network\\MgmtLqiRspCommand", $parent->getPayloadObject());
    $this->assertEquals($base_frame->displayFrame(), $parent->displayPayload());
    }
  }
 