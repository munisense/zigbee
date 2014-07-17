<?php

namespace Munisense\Zigbee\ZCL\General;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\ZCL\IZCLCommandFrame;
use Munisense\Zigbee\ZCL\ZCLFrame;

/**
 * Class ReportAttributesCommand
 * @package Munisense\Zigbee\ZCL\General
 *
 * The report attributes command is used by a device to report the values of one or
 * more of its attributes to another device, bound a priori. Individual clusters, defined
 * elsewhere in the ZCL, define which attributes are to be reported and at what
 * interval.
 */
class ReportAttributesCommand extends AbstractFrame implements IZCLCommandFrame
  {
  /**
   * @var AttributeReport[]
   */
  private $attribute_reports = array();

  public function setFrame($frame)
    {
    while(strlen($frame))
      {
      $attribute_report = new AttributeReport();
      $attribute_report->consumeFrame($frame);
      $this->addAttributeReport($attribute_report);
      }
    }

  public function getFrame()
    {
    $frame = "";

    foreach($this->attribute_reports as $report_attributes_element)
      $frame .= $report_attributes_element->getFrame();

    return $frame;
    }

  public function setAttributeReports(array $attribute_reports)
    {
    $this->attribute_reports = [];
    foreach($attribute_reports as $attribute_report)
      $this->addAttributeReport($attribute_report);
    }

  public function getAttributeReports()
    {
    return $this->attribute_reports;
    }

  public function addAttributeReport(AttributeReport $attribute_report)
    {
    $this->attribute_reports[] = $attribute_report;
    }

  public function __toString()
    {
    $output =  __CLASS__." (count: ".count($this->getAttributeReports()).", length: ".strlen($this->getFrame()).")".PHP_EOL;
    foreach($this->getAttributeReports() as $attribute_report)
      $output .= "|- ".$attribute_report.PHP_EOL;

    return $output;
    }


  /**
   * Returns the Command ID of this frame
   * @return int
   */
  public function getCommandId()
    {
    return GeneralCommand::REPORT_ATTRIBUTES;
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

