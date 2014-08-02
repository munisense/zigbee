<?php

namespace Munisense\Zigbee\ZCL\General;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\ZigbeeException;
use Munisense\Zigbee\ZCL\IZCLCommandFrame;
use Munisense\Zigbee\ZCL\ZCLFrame;
use Munisense\Zigbee\ZCL\ZCLStatus;

/**
 * The write attributes response command is generated in response to a write
 * attributes command.
 *
 * @package Munisense\Zigbee
 */
class WriteAttributesResponseCommand extends AbstractFrame implements IZCLCommandFrame
  {
  /**
   * @var WriteAttributeStatusRecord[]
   */
  private $write_attribute_status_records = array();
  
  public static function construct(array $write_attribute_status_records = array())
    {
    $frame = new self;
    $frame->setWriteAttributeStatusRecords($write_attribute_status_records);
    return $frame;
    }

  public function isSuccess()
    {
    return count($this->write_attribute_status_records) == 0;
    }

  public function setFrame($frame)
    {
    /**
     * If there are no write attribute status records in the constructed command, indicating that all attributes were
     * written successfully, a single write attribute status record shall be included in the
     * command, with the status field set to SUCCESS and the attribute identifier field
     * omitted.
     */
    if(strlen($frame) == 1)
      {
      $status = Buffer::unpackInt8u($frame);
      if($status != ZCLStatus::SUCCESS)
        throw new ZigbeeException("If a ".__CLASS__." only has one byte, it should be the SUCCESS status");

      return;
      }
    else
      while(strlen($frame))
        {
        $write_attribute_status_record = new WriteAttributeStatusRecord();
        $write_attribute_status_record->consumeFrame($frame);
        $this->write_attribute_status_records[] = $write_attribute_status_record;
        }
    }

  public function getFrame()
    {
    $frame = "";

    // If there are no records, just send a single SUCCESS
    if(empty($this->write_attribute_status_records))
      Buffer::packInt8u($frame, ZCLStatus::SUCCESS);
    else
      // Loop over the different records
      foreach($this->write_attribute_status_records as $write_attribute_status_record)
        $frame .= $write_attribute_status_record->getFrame();

    return $frame;
    }

  public function setWriteAttributeStatusRecords(array $write_attribute_status_records)
    {
    $this->write_attribute_status_records = [];
    foreach($write_attribute_status_records as $write_attribute_status_record)
      $this->addWriteAttributeStatusRecord($write_attribute_status_record);
    }

  public function getWriteAttributeStatusRecords()
    {
    return $this->write_attribute_status_records;
    }

  public function addWriteAttributeStatusRecord(WriteAttributeStatusRecord $write_attribute_status_record)
    {
    if($write_attribute_status_record->getStatus() == ZCLStatus::SUCCESS)
      throw new ZigbeeException("Attributes with status SUCCESS should be omitted");

    $this->write_attribute_status_records[] = $write_attribute_status_record;
    }

  public function __toString()
    {
    $output =  __CLASS__." (count: ".count($this->getWriteAttributeStatusRecords()).", length: ".strlen($this->getFrame()).")".PHP_EOL;
    $write_attribute_status_records = $this->getWriteAttributeStatusRecords();
    $write_attribute_status_records_count = count($write_attribute_status_records);
    foreach($write_attribute_status_records as $key => $write_attribute_status_record)
      $output .= ($key + 1 == $write_attribute_status_records_count ? "`" : "|")."- ".$write_attribute_status_record.PHP_EOL;

    return $output;
    }


  /**
   * Returns the Command ID of this frame
   * @return int
   */
  public function getCommandId()
    {
    return GeneralCommand::WRITE_ATTRIBUTES_RESPONSE;
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

