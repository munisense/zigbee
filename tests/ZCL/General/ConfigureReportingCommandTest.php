<?php

namespace Munisense\Zigbee\ZCL\General;

class ConfigureReportingCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testStaticConstruct()
    {
    $zcl = ConfigureReportingCommand::construct([
        AttributeReportingConfigurationRecord::constructReceived(0x1234, 60),
        AttributeReportingConfigurationRecord::constructReported(0x4567, 0x23, 60, 10, 12)
    ]);

    $this->assertEquals('0x01 0x34 0x12 0x3c 0x00 0x00 0x67 0x45 0x23 0x3c 0x00 0x0a 0x00 0x0c 0x00 0x00 0x00', $zcl->displayFrame());
    }

  public function testReverse()
    {
    $old_zcl = ConfigureReportingCommand::construct([
        AttributeReportingConfigurationRecord::constructReceived(0x1234, 60),
        AttributeReportingConfigurationRecord::constructReported(0x4567, 0x23, 60, 10, 12)
    ]);

    $old_zcl_frame = $old_zcl->getFrame();

    $new = new ConfigureReportingCommand($old_zcl_frame);
    $this->assertEquals($old_zcl_frame, $new->getFrame());
    }
  }
 