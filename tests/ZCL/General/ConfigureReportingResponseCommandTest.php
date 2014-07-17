<?php

namespace Munisense\Zigbee\ZCL\General;


use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\ZCL\ZCLStatus;

class ConfigureReportingResponseCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testAllSuccess()
    {
    Buffer::packInt8u($payload, ZCLStatus::SUCCESS);
    $frame = new WriteAttributesResponseCommand($payload);
    $this->assertTrue($frame->isSuccess());
    $this->assertEquals("0x00", $frame->displayFrame());
    }

  public function testStaticConstruct()
    {
    $zcl = ConfigureReportingResponseCommand::construct([
        AttributeStatusRecord::construct(ZCLStatus::UNREPORTABLE_ATTRIBUTE, AttributeReportingConfigurationStatusRecord::DIRECTION_SERVER_TO_CLIENT , 0x1234),
        AttributeStatusRecord::construct(ZCLStatus::CALIBRATION_ERROR, AttributeReportingConfigurationStatusRecord::DIRECTION_SERVER_TO_CLIENT , 0x4567)
    ]);

    $this->assertEquals('0x8c 0x00 0x34 0x12 0xc2 0x00 0x67 0x45', $zcl->displayFrame());
    }

  public function testReverse()
    {
    $old_zcl = ConfigureReportingResponseCommand::construct([
        AttributeStatusRecord::construct(ZCLStatus::UNREPORTABLE_ATTRIBUTE, AttributeReportingConfigurationStatusRecord::DIRECTION_SERVER_TO_CLIENT , 0x1234),
        AttributeStatusRecord::construct(ZCLStatus::CALIBRATION_ERROR, AttributeReportingConfigurationStatusRecord::DIRECTION_SERVER_TO_CLIENT , 0x4567)
    ]);

    $old_zcl_frame = $old_zcl->getFrame();

    $new = new ConfigureReportingResponseCommand($old_zcl_frame);
    $this->assertEquals($old_zcl_frame, $new->getFrame());
    }
  }
 