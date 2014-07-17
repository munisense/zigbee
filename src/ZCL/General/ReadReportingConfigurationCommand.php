<?php

namespace Munisense\Zigbee\ZCL\General;

use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\ZCL\IZCLCommandFrame;
use Munisense\Zigbee\ZCL\ZCLFrame;

/**
 * Class ReadReportingConfigurationCommand
 * @package Munisense\Zigbee
 *
 * The Read Reporting Configuration command is used to read the configuration
 * details of the reporting mechanism for one or more of the attributes of a cluster.
 */
class ReadReportingConfigurationCommand extends AbstractFrame implements IZCLCommandFrame
  {
  /**
   * @var AttributeRecord[]
   */
  private $attribute_records = array();

  /**
   * @param AttributeRecord[] $elements
   * @return ReadReportingConfigurationCommand
   */
  public static function construct(array $elements = array())
    {
    $frame = new self;

    foreach($elements as $element)
      $frame->addAttributeRecord($element);

    return $frame;
    }

  public function setFrame($frame)
    {
    while(strlen($frame))
      {
      $attribute_record = new AttributeRecord();
      $attribute_record->consumeFrame($frame);
      $this->attribute_records[] = $attribute_record;
      }
    }

  public function getFrame()
    {
    $frame = "";

    foreach($this->attribute_records as $attribute_record)
      $frame .= $attribute_record->getFrame();

    return $frame;
    }

  /**
   * @param AttributeRecord[] $attribute_records
   */
  public function setAttributeRecords(array $attribute_records)
    {
    $this->attribute_records = [];
    foreach($attribute_records as $attribute_record)
      $this->addAttributeRecord($attribute_record);

    $this->attribute_records = $attribute_records;
    }

  /**
   * @return AttributeRecord[]
   */
  public function getAttributeRecords()
    {
    return $this->attribute_records;
    }

  /**
   * @param AttributeRecord $attribute_record
   */
  public function addAttributeRecord(AttributeRecord $attribute_record)
    {
    $this->attribute_records[] = $attribute_record;
    }

  public function __toString()
    {
    $output =  __CLASS__." (count: ".count($this->getAttributeRecords()).", length: ".strlen($this->getFrame()).")".PHP_EOL;
    $attribute_records = $this->getAttributeRecords();
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
    return GeneralCommand::READ_REPORTING_CONFIGURATION;
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

