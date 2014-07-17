<?php

namespace Munisense\Zigbee\ZCL\General;

use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\ZCL\ZCLFrame;
use Munisense\Zigbee\ZCL\ZCLStatus;

class WriteAttributesResponseCommandTest extends \PHPUnit_Framework_TestCase
  {
  private $zcl_str;
  /**
   * @var WriteAttributesResponseCommand
   */
  private $zcl_frame;
  private $zcl_write_attribute_status_record_0;
  private $zcl_write_attribute_status_record_1;

  public function setUp()
    {
    $this->zcl_str = chr(0xc2).chr(0x21).chr(0x00).chr(0xc0).chr(0x22).chr(0x00);
    $this->zcl_write_attribute_status_record_0 = WriteAttributeStatusRecord::construct(ZCLStatus::CALIBRATION_ERROR, 0x21);
    $this->zcl_write_attribute_status_record_1 = WriteAttributeStatusRecord::construct(ZCLStatus::HARDWARE_FAILURE, 0x22);
    $this->zcl_frame = WriteAttributesResponseCommand::construct([
        $this->zcl_write_attribute_status_record_0, $this->zcl_write_attribute_status_record_1
    ]);
    }

  public function testDisplayFrame()
    {
    $this->assertEquals('0xc2 0x21 0x00 0xc0 0x22 0x00', $this->zcl_frame->displayFrame());
    }

  public function testReverse()
    {
    $old_zcl_frame = $this->zcl_frame->getFrame();
    $new = new ZCLFrame($old_zcl_frame);
    $this->assertEquals($old_zcl_frame, $new->getFrame());
    }

  public function testAllSuccess()
    {
    Buffer::packInt8u($payload, ZCLStatus::SUCCESS);
    $frame = new WriteAttributesResponseCommand($payload);
    $this->assertTrue($frame->isSuccess());
    $this->assertEquals("0x00", $frame->displayFrame());
    }

  public function testSetFrame()
    {
    $frame = new WriteAttributesResponseCommand($this->zcl_str);

    $records = $frame->getWriteAttributeStatusRecords();
    $this->assertEquals(2, count($records));

    $this->assertEquals($this->zcl_write_attribute_status_record_0, $records[0]);
    $this->assertEquals($this->zcl_write_attribute_status_record_1, $records[1]);
    }
  }