<?php

namespace Munisense\Zigbee\ZDP\Discovery;

class NodeDescriptorTest extends \PHPUnit_Framework_TestCase
  {
  public function testReverse()
    {
    $node_descriptor = NodeDescriptor::construct(NodeDescriptor::ZIGBEE_ROUTER, 1, 1, 0, NodeDescriptor::FREQUENCY_SUPPORTED_868MHZ ^ NodeDescriptor::FREQUENCY_SUPPORTED_2400_2483MHZ,
      NodeDescriptor::PAN_COORDINATOR_CAPABLE, NodeDescriptor::DEVICE_TYPE_FFD, NodeDescriptor::POWER_SOURCE_MAINS, NodeDescriptor::RECEIVER_ON_WHEN_IDLE, NodeDescriptor::SECURE_CAPABLE,
      0, 0xffff, 0x7f, 0x0a, 0x0a, 0x7fff, 1, 1
    );

    $frame = $node_descriptor->getFrame();
    $new_descriptor = new NodeDescriptor($frame);

    $this->assertEquals($node_descriptor->getFrame(), $new_descriptor->getFrame());
    }

  /**
   * @expectedException \Munisense\Zigbee\Exception\ZigbeeException
   */
  public function testSetMaximumOutgoingTransferSize__Invalid()
     {
     $node_descriptor = new NodeDescriptor();
     $node_descriptor->setMaximumOutgoingTransferSize(0x7fff + 1);
     }

  public function testSetMaximumOutgoingTransferSize()
    {
    $valid_elems = [0, 0xff, 0x7fff];
    $node_descriptor = new NodeDescriptor();

    foreach($valid_elems as $elem)
      $node_descriptor->setMaximumOutgoingTransferSize($elem);
    }

  /**
   * @expectedException \Munisense\Zigbee\Exception\ZigbeeException
   */
  public function testSetMaximumIncomingTransferSize__Invalid()
    {
    $node_descriptor = new NodeDescriptor();
    $node_descriptor->setMaximumIncomingTransferSize(0x7fff + 1);
    }

  public function testSetMaximumIncomingTransferSize()
    {
    $valid_elems = [0, 0xff, 0x7fff];
    $node_descriptor = new NodeDescriptor();

    foreach($valid_elems as $elem)
    $node_descriptor->setMaximumIncomingTransferSize($elem);
    }
  }
 