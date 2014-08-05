<?php
namespace Munisense\Zigbee\ZDP\Discovery;

class NodePowerDescriptorTest extends \PHPUnit_Framework_TestCase
  {
  public function testStaticConstruct()
    {
    $power_desc = NodePowerDescriptor::construct(NodePowerDescriptor::MODE_RECEIVER_STIMULATED, NodePowerDescriptor::SOURCE_DISPOSABLE_BATTERY ^ NodePowerDescriptor::SOURCE_CONSTANT_MAINS_POWER,
      NodePowerDescriptor::SOURCE_DISPOSABLE_BATTERY, NodePowerDescriptor::LEVEL_33PERC
    );

    $this->assertEquals("0x52 0x44", $power_desc->displayFrame());
    }

  public function testReverse()
    {
    $power_desc = NodePowerDescriptor::construct(NodePowerDescriptor::MODE_RECEIVER_STIMULATED, NodePowerDescriptor::SOURCE_DISPOSABLE_BATTERY ^ NodePowerDescriptor::SOURCE_CONSTANT_MAINS_POWER,
      NodePowerDescriptor::SOURCE_DISPOSABLE_BATTERY, NodePowerDescriptor::LEVEL_33PERC
    );

    $frame = $power_desc->getFrame();
    $new_descriptor = new NodePowerDescriptor($frame);

    $this->assertEquals($power_desc->getFrame(), $new_descriptor->getFrame());
    }

  public function testDisplayPowerSource()
    {
    $power_desc = new NodePowerDescriptor();
    $this->assertEquals("RECHARGEABLE_BATTERY", $power_desc->displayPowerSource(NodePowerDescriptor::SOURCE_RECHARGEABLE_BATTERY));
    $this->assertEquals("RECHARGEABLE_BATTERY, CONSTANT_MAINS_POWER", $power_desc->displayPowerSource(NodePowerDescriptor::SOURCE_CONSTANT_MAINS_POWER ^ NodePowerDescriptor::SOURCE_RECHARGEABLE_BATTERY));
    }
  }
 