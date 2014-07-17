<?php

namespace Munisense\Zigbee\ZCL\General;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\ZCL\IZCLCommandFrame;
use Munisense\Zigbee\ZCL\ZCLFrame;

/**
 * Class ReadReportingConfigurationResponseCommand
 * @package Munisense\Zigbee
 *
 * The Read Reporting Configuration Response command is used to respond to a
 * Read Reporting Configuration command.
 */
class ReadReportingConfigurationResponseCommand extends AbstractFrame implements IZCLCommandFrame
  {
  /**
   * There shall be one attribute reporting configuration record for each attribute
   * record of the received read reporting configuration command.
   *
   * @var AttributeReportingConfigurationStatusRecord[]
   */
  private $attribute_reporting_configuration_status_records = array();

  /**
   * @param AttributeReportingConfigurationStatusRecord[] $elements
   * @return ReadReportingConfigurationCommand
   */
  public static function construct(array $elements = array())
    {
    $frame = new self;

    foreach($elements as $element)
      $frame->addAttributeReportingConfigurationStatusRecord($element);

    return $frame;
    }

  public function setFrame($frame)
    {
    while(strlen($frame))
      {
      $attribute_record = new AttributeReportingConfigurationStatusRecord();
      $attribute_record->consumeFrame($frame);
      $this->attribute_reporting_configuration_status_records[] = $attribute_record;
      }
    }

  public function getFrame()
    {
    $frame = "";

    foreach($this->attribute_reporting_configuration_status_records as $attribute_record)
      $frame .= $attribute_record->getFrame();

    return $frame;
    }

  public function setAttributeReportingConfigurationStatusRecords(array $attribute_records)
    {
    $this->attribute_reporting_configuration_status_records = [];
    foreach($attribute_records as $attribute_record)
      $this->addAttributeReportingConfigurationStatusRecord($attribute_record);
    }

  public function getAttributeReportingConfigurationStatusRecords()
    {
    return $this->attribute_reporting_configuration_status_records;
    }

  public function addAttributeReportingConfigurationStatusRecord(AttributeReportingConfigurationStatusRecord $attribute_record)
    {
    $this->attribute_reporting_configuration_status_records[] = $attribute_record;
    }

  public function __toString()
    {
    $output =  __CLASS__." (count: ".count($this->getAttributeReportingConfigurationStatusRecords()).", length: ".strlen($this->getFrame()).")".PHP_EOL;
    $attribute_records = $this->getAttributeReportingConfigurationStatusRecords();
    $attribute_records_count = count($attribute_records);
    foreach($attribute_records as $key => $attribute_record)
      $output .= ($key + 1 == $attribute_records_count ? "`" : "|")."- ".$attribute_record.PHP_EOL;

    return $output;
    }


  /**
   * Returns the Command ID of this frame
   * @return int
   */
  public function getCommandId()
    {
    return GeneralCommand::READ_REPORTING_CONFIGURATION_RESPONSE;
    }

  /**
   * Returns the Frame Type of this frame
   * @return int
   */
  public function getFrameType()
    {
    return ZCLFrame::FRAME_TYPE_PROFILE_WIDE;
    }
  }

