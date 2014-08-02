<?php

namespace Munisense\Zigbee\ZCL\General;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\ZigbeeException;
use Munisense\Zigbee\ZCL\IZCLCommandFrame;
use Munisense\Zigbee\ZCL\ZCLFrame;
use Munisense\Zigbee\ZCL\ZCLStatus;

class ConfigureReportingResponseCommand extends AbstractFrame implements IZCLCommandFrame
  {
  /**
   * @var AttributeStatusRecord[]
   */
  private $attribute_status_records = array();

  /**
   * @param AttributeStatusRecord[] $attribute_status_records
   * @return ConfigureReportingResponseCommand
   */
  public static function construct(array $attribute_status_records = array())
    {
    $frame = new self;
    $frame->setAttributeStatusRecords($attribute_status_records);
    return $frame;
    }

  public function isSuccess()
    {
    return count($this->attribute_status_records) == 0;
    }

  public function setFrame($frame)
    {
    /**
     * Note that attribute status records are not included for successfully configured
     * attributes, in order to save bandwidth. In the case of successful configuration of all
     * attributes, only a single attribute statusrecord shall be included in the command,
     * with the status field set to SUCCESS and the direction and attribute identifier
     * fields omitted
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
        $attribute_status_record = new AttributeStatusRecord();
        $attribute_status_record->consumeFrame($frame);
        $this->addAttributeStatusRecord($attribute_status_record);
        }
    }

  public function getFrame()
    {
    $frame = "";

    if(!empty($this->attribute_status_records))
      {
      foreach($this->attribute_status_records as $attribute_status_record)
        $frame .= $attribute_status_record->getFrame();
      }
    else
      Buffer::packInt8u($frame, ZCLStatus::SUCCESS);

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

