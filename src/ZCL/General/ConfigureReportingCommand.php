<?php

namespace Munisense\Zigbee\ZCL\General;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\ZCL\IZCLCommandFrame;
use Munisense\Zigbee\ZCL\ZCLFrame;

class ConfigureReportingCommand extends AbstractFrame implements IZCLCommandFrame
  {
  /**
   * @var AttributeReportingConfigurationRecord[]
   */
  private $attribute_reporting_configuration_records = array();

  /**
   * @param AttributeReportingConfigurationRecord[] $attribute_reporting_configuration_records
   * @return ConfigureReportingCommand
   */
  public static function construct(array $attribute_reporting_configuration_records = array())
    {
    $frame = new self;
    $frame->setAttributeReportingConfigurationRecords($attribute_reporting_configuration_records);
    return $frame;
    }

  public function setFrame($frame)
    {
    while(strlen($frame))
      {
      $configure_reporting_element = new AttributeReportingConfigurationRecord();
      $configure_reporting_element->consumeFrame($frame);
      $this->addAttributeReportingConfigurationRecord($configure_reporting_element);
      }
    }

  public function getFrame()
    {
    $frame = "";

    foreach($this->attribute_reporting_configuration_records as $configure_reporting_element)
      $frame .= $configure_reporting_element->getFrame();

    return $frame;
    }

  public function setAttributeReportingConfigurationRecords(array $configure_reporting_elements)
    {
    $this->attribute_reporting_configuration_records = [];
    foreach($configure_reporting_elements as $configure_reporting_element)
      $this->addAttributeReportingConfigurationRecord($configure_reporting_element);

    $this->attribute_reporting_configuration_records = $configure_reporting_elements;
    }

  public function getAttributeReportingConfigurationRecords()
    {
    return $this->attribute_reporting_configuration_records;
    }

  public function addAttributeReportingConfigurationRecord(AttributeReportingConfigurationRecord $configure_reporting_element)
    {
    $this->attribute_reporting_configuration_records[] = $configure_reporting_element;
    }

  public function __toString()
    {
    $x = 0;
    $count = count($this->getAttributeReportingConfigurationRecords());
    $output =  __CLASS__." (count: ".$count.", length: ".strlen($this->getFrame()).")".PHP_EOL;
    foreach($this->getAttributeReportingConfigurationRecords() as $configure_reporting_element)
      $output .= (++$x == $count ? "`" : "|" )."- ".$configure_reporting_element.PHP_EOL;

    return $output;
    }


  /**
   * Returns the Command ID of this frame
   * @return int
   */
  public function getCommandId()
    {
    return GeneralCommand::CONFIGURE_REPORTING;
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

