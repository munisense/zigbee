<?php

namespace Munisense\Zigbee\ZCL;
use Munisense\Zigbee\ZCL\IAS_Zone\IAS_Zone;

class ClusterFactoryTest extends \PHPUnit_Framework_TestCase
  {
  public function testGetClusterClassInstance()
    {
    $this->assertInstanceOf("Munisense\\Zigbee\\ZCL\\IAS_Zone\\IAS_Zone", ClusterFactory::getClusterClassInstance(IAS_Zone::CLUSTER_ID));
    }

  /**
   * @throws \Munisense\Zigbee\Exception\ZigbeeException
   * @expectedException \Munisense\Zigbee\Exception\ZigbeeException
   */
  public function testgetClusterClassInstance__Invalid()
    {
    ClusterFactory::getClusterClassInstance(0xffff);
    }
  }
 