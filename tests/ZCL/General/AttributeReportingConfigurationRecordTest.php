<?php

namespace Munisense\Zigbee\ZCL\General;

use Munisense\Zigbee\ZCL\ZCLStatus;

class AttributeReportingConfigurationRecordTest extends \PHPUnit_Framework_TestCase
  {
  public function testConstructReceived()
    {
    $record = AttributeReportingConfigurationRecord::constructReceived(
      0x1234, 60
    );

    $this->assertEquals("0x01 0x34 0x12 0x3c 0x00", $record->displayFrame());
    }

  public function testConstructReported()
    {
    $record = AttributeReportingConfigurationRecord::constructReported(
        0x1234, 0x23, 60, 10, 5
    );
    $this->assertEquals("0x00 0x34 0x12 0x23 0x3c 0x00 0x0a 0x00 0x05 0x00 0x00 0x00", $record->displayFrame());
    }
  }
 