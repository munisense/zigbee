<?php

namespace Munisense\Zigbee\ZCL\General;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Exception\MuniZigbeeException;
use Munisense\Zigbee\ZCL\IZCLCommandFrame;
use Munisense\Zigbee\ZCL\ZCLFrame;

class ConfigureReportingResponseCommand extends AbstractFrame implements IZCLCommandFrame
  {
  /**
   * @var AttributeStatusRecord[]
   */
  private $attribute_status_records = array();

  public function setFrame($frame)
    {
    while(strlen($frame))
      {
      $attribute_status_record = new AttributeReportingConfigurationRecord($frame);
      $this->attribute_status_records[] = $attribute_status_record;
      }
    }

  public function getFrame()
    {
    $frame = "";

    foreach($this->attribute_status_records as $attribute_status_record)
      $frame .= $attribute_status_record->getFrame();

    return $frame;
    }

  public function setAttributeStatusRecords(array $attribute_status_records)
    {
    foreach($attribute_status_records as $attribute_status_record)
      $this->addAttributeStatusRecord($attribute_status_record);
    }

  public function getAttributeStatusRecords()
    {
    return $this->attribute_status_records;
    }

  public function addAttributeStatusRecord(AttributeStatusRecord $attribute_status_records)
    {
    $this->attribute_status_records[] = $attribute_status_records;
    }

  public function __toString()
    {
    $x = 0;
    $count = count($this->getAttributeStatusRecords());
    $output =  __CLASS__." (count: ".$count.", length: ".strlen($this->getFrame()).")".PHP_EOL;
    foreach($this->getAttributeStatusRecords() as $attribute_status_record)
      $output .= (++$x == $count ? "`" : "|" )."- ".$attribute_status_record.PHP_EOL;

    return $output;
    }


  /**
   * Returns the Command ID of this frame
   * @return int
   */
  public function getCommandId()
    {
    return GeneralCommand::CONFIGURE_REPORTING_RESPONSE;
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

