<?php

namespace Munisense\Zigbee\ZCL\General;

use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\ZCL\IZCLCommandFrame;
use Munisense\Zigbee\ZCL\ZCLFrame;

class ReadAttributesResponseCommand extends AbstractFrame implements IZCLCommandFrame
  {
  /**
   * @var ReadAttributesStatusRecord[]
   */
  private $read_attributes_status_records = array();

  public static function construct(array $elements = array())
    {
    $frame = new self;
    $frame->read_attributes_status_records = $elements;
    return $frame;
    }

  public function setFrame($frame)
    {
    while(strlen($frame))
      {
      $status_record = new ReadAttributesStatusRecord();
      $status_record->consumeFrame($frame);
      $this->read_attributes_status_records[] = $status_record;
      }
    }

  public function getFrame()
    {
    $frame = "";

    foreach($this->read_attributes_status_records as $read_attributes_element)
      $frame .= $read_attributes_element->getFrame();

    return $frame;
    }


  public function setReadAttributesStatusRecords(array $read_attributes_status_records)
    {
    $this->read_attributes_status_records = [];
    foreach($read_attributes_status_records as $read_attributes_status_record)
      $this->addReadAttributesElement($read_attributes_status_record);
    }

  public function getReadAttributesStatusRecords()
    {
    return $this->read_attributes_status_records;
    }

  public function addReadAttributesElement(ReadAttributesStatusRecord $read_attributes_status_record)
    {
    $this->read_attributes_status_records[] = $read_attributes_status_record;
    }

  public function __toString()
    {
    $output =  __CLASS__." (count: ".count($this->getReadAttributesStatusRecords()).", length: ".strlen($this->getFrame()).")".PHP_EOL;
    $read_attributes_elements = $this->getReadAttributesStatusRecords();
    $read_attributes_elements_count = count($read_attributes_elements);
    foreach($read_attributes_elements as $key => $read_attributes_element)
      $output .= ($key + 1 == $read_attributes_elements_count ? "`" : "|")."- ".$read_attributes_element.PHP_EOL;

    return $output;
    }


  /**
   * Returns the Command ID of this frame
   * @return int
   */
  public function getCommandId()
    {
    return GeneralCommand::READ_ATTRIBUTES_RESPONSE;
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

