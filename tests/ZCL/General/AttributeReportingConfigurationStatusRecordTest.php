<?php

namespace Munisense\Zigbee\ZCL\General;


use Munisense\Zigbee\Exception\ZigbeeException;
use Munisense\Zigbee\ZCL\ZCLStatus;

class AttributeReportingConfigurationStatusRecordTest extends \PHPUnit_Framework_TestCase
  {
  /**
   * Not all statusses are allowed according to the spec 2.4.10.1.2
   */
  public function testAllowedStatusses()
    {
    $record = new AttributeReportingConfigurationStatusRecord();

    $allowed_statusses = [ZCLStatus::UNSUPPORTED_ATTRIBUTE, ZCLStatus::UNREPORTABLE_ATTRIBUTE, ZCLStatus::SUCCESS];

    // Some random other statusses that are not whitelisted
    $disallowed_statusses = [ZCLStatus::CALIBRATION_ERROR, ZCLStatus::INSUFFICIENT_SPACE, ZCLStatus::UNSUP_CLUSTER_COMMAND];

    foreach($allowed_statusses as $allowed_status)
      {
      $record->setStatus($allowed_status);
      $this->assertEquals($allowed_status, $record->getStatus());
      }

    foreach($disallowed_statusses as $disallowed_status)
      {
      try
        {
        $record->setStatus($disallowed_status);
        $this->fail("setStatus should not accept status " . ZCLStatus::displayStatus($disallowed_status));
        }
      catch(ZigbeeException $e) {}
      }
    }

  /**
   * If the status field is not set to SUCCESS, all fields except the direction and
   * attribute identifier fields shall be omitted.
   */
  public function testErrorFrame()
    {
    $record = new AttributeReportingConfigurationStatusRecord();
    $record->setStatus(ZCLStatus::UNSUPPORTED_ATTRIBUTE);
    $record->setAttributeId(0x1234);
    $record->setDatatypeId(0x23);

    $this->assertEquals("0x86 0x00 0x34 0x12", $record->displayFrame());
    }

  public function testConstructWithError()
    {
    $record = AttributeReportingConfigurationStatusRecord::constructWithError(
      ZCLStatus::UNSUPPORTED_ATTRIBUTE,
      AttributeReportingConfigurationStatusRecord::DIRECTION_SERVER_TO_CLIENT,
      0x0023
    );

    $this->assertEquals("0x86 0x00 0x23 0x00", $record->displayFrame());
    }

  public function testConstructorSuccess()
    {
    $record = AttributeReportingConfigurationStatusRecord::constructSuccess(
      AttributeReportingConfigurationRecord::constructReceived(0x1234, 60)
    );

    $this->assertEquals("0x00 0x01 0x34 0x12 0x3c 0x00", $record->displayFrame());
    }

  public function testGetAttributeReportingConfigurationRecord()
    {
    // Test with a Received Object
    $parent = AttributeReportingConfigurationRecord::constructReceived(0x1234, 60);
    $record = AttributeReportingConfigurationStatusRecord::constructSuccess($parent);
    $config_record = $record->getAttributeReportingConfigurationRecord();
    $this->assertInstanceOf("Munisense\\Zigbee\\ZCL\\General\\AttributeReportingConfigurationRecord", $config_record);
    $this->assertEquals($parent->displayFrame(), $config_record->displayFrame());

    // Test with a Reported Object
    $parent = AttributeReportingConfigurationRecord::constructReported(0x1234, 60, 400, 200, 12);
    $record = AttributeReportingConfigurationStatusRecord::constructSuccess($parent);
    $config_record = $record->getAttributeReportingConfigurationRecord();
    $this->assertInstanceOf("Munisense\\Zigbee\\ZCL\\General\\AttributeReportingConfigurationRecord", $config_record);
    $this->assertEquals($parent->displayFrame(), $config_record->displayFrame());
    }

  /**
   * @expectedException \Munisense\Zigbee\Exception\ZigbeeException
   */
  public function testGetAttributeReportingConfigurationRecord_Failure()
    {
    $record = AttributeReportingConfigurationStatusRecord::constructWithError(ZCLStatus::UNREPORTABLE_ATTRIBUTE, AttributeRecord::DIRECTION_SERVER_TO_CLIENT, 0x0012);

    // Should throw an exception
    $record->getAttributeReportingConfigurationRecord();
    }
  }
 