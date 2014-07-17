<?php

namespace Munisense\Zigbee\ZCL\IAS_Zone;


use Munisense\Zigbee\ZCL\Cluster;
use Munisense\Zigbee\ZCL\ZCLFrame;

class ZoneStatusChangeNotificationCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testConstruct()
    {
    $command = new ZoneStatusChangeNotificationCommand(chr(0x5A).chr(0x00).chr(0x00));
    $this->assertEquals(0x5A, $command->getZoneStatus()->getValue());
    }

  public function testStaticConstruct()
    {
    $command = ZoneStatusChangeNotificationCommand::construct(
      new ZoneStatus(0x5A), 0x00
    );

    $this->assertEquals(0x5A, $command->getZoneStatus()->getValue());
    }

  public function testFitInZCLFrame()
    {
    $bytes = chr(0x5A).chr(0x00).chr(0x00);

    $command = new ZoneStatusChangeNotificationCommand($bytes);

    $zcl_frame = new ZCLFrame();
    $zcl_frame->setPayloadObject($command);

    /**
     * @var $output ZoneStatusChangeNotificationCommand
     */
    $output = $zcl_frame->getPayloadObject(Cluster::IAS_Zone);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZCL\\IAS_Zone\\ZoneStatusChangeNotificationCommand", $output);
    $this->assertEquals($bytes, $output->getFrame());
    }
  }
 