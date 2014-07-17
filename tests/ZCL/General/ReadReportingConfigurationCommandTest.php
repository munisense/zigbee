<?php

namespace Munisense\Zigbee\ZCL\General;

class ReadReportingConfigurationCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testStaticConstruct()
    {
    $zcl = ReadReportingConfigurationCommand::construct([
        AttributeRecord::construct(AttributeRecord::DIRECTION_CLIENT_TO_SERVER, 0x0102),
        AttributeRecord::construct(AttributeRecord::DIRECTION_SERVER_TO_CLIENT, 0x0304)
    ]);

    $this->assertEquals('0x01 0x02 0x01 0x00 0x04 0x03', $zcl->displayFrame());
    }

  public function testReverse()
    {
    $old_zcl = ReadReportingConfigurationCommand::construct([
        AttributeRecord::construct(AttributeRecord::DIRECTION_CLIENT_TO_SERVER, 0x0102),
        AttributeRecord::construct(AttributeRecord::DIRECTION_SERVER_TO_CLIENT, 0x0304)
    ]);

    $old_zcl_frame = $old_zcl->getFrame();

    $new = new ReadReportingConfigurationCommand($old_zcl_frame);

    $this->assertEquals($old_zcl_frame, $new->getFrame());
    }
  }