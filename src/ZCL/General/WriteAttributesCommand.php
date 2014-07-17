<?php

namespace Munisense\Zigbee\ZCL\General;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\ZCL\IZCLCommandFrame;
use Munisense\Zigbee\ZCL\ZCLFrame;

class WriteAttributesCommand extends AbstractFrame implements IZCLCommandFrame
  {
  /**
   * @var WriteAttributeRecord[]
   */
  private $write_attribute_records = array();

  /**
   * @param WriteAttributeRecord[] $write_attribute_records
   * @return WriteAttributesCommand
   */
  public static function construct(array $write_attribute_records = array())
    {
    $frame = new self;
    $frame->setWriteAttributeRecords($write_attribute_records);
    return $frame;
    }

  public function setFrame($frame)
    {
    while(strlen($frame))
      {
      $write_attribute_record = new WriteAttributeRecord();
      $write_attribute_record->consumeFrame($frame);
      $this->write_attribute_records[] = $write_attribute_record;
      }
    }

  public function getFrame()
    {
    $frame = "";

    foreach($this->write_attribute_records as $write_attribute_record)
      $frame .= $write_attribute_record->getFrame();

    return $frame;
    }

  public function setWriteAttributeRecords(array $write_attribute_records)
    {
    $this->write_attribute_records = [];
    foreach($write_attribute_records as $write_attribute_record)
      $this->addWriteAttributeRecord($write_attribute_record);
    }

  public function getWriteAttributeRecords()
    {
    return $this->write_attribute_records;
    }

  public function addWriteAttributeRecord(WriteAttributeRecord $write_attribute_record)
    {
    $this->write_attribute_records[] = $write_attribute_record;
    }

  public function __toString()
    {
    $output =  __CLASS__." (count: ".count($this->getWriteAttributeRecords()).", length: ".strlen($this->getFrame()).")".PHP_EOL;
    $write_attribute_records = $this->getWriteAttributeRecords();
    $write_attributes_elements_count = count($write_attribute_records);
    foreach($write_attribute_records as $key => $write_attribute_record)
      $output .= ($key + 1 == $write_attributes_elements_count ? "`" : "|")."- ".$write_attribute_record.PHP_EOL;

    return $output;
    }


  /**
   * Returns the Command ID of this frame
   * @return int
   */
  public function getCommandId()
    {
    return GeneralCommand::WRITE_ATTRIBUTES;
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

