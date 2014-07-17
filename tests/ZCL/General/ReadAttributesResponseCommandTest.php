<?php

namespace Munisense\Zigbee\ZCL\General;

use Munisense\Zigbee\ZCL\ZCLFrame;
use Munisense\Zigbee\ZCL\ZCLStatus;

class ReadAttributesResponseCommandTest extends \PHPUnit_Framework_TestCase
  {
  private $zcl_str;

  /**
   * @var ReadAttributesResponseCommand
   */
  private $zcl_frame;
  private $zcl_read_attribute_status_record_0;
  private $zcl_read_attribute_status_record_1;

  public function setUp()
    {
    $this->zcl_str = chr(0x02).chr(0x01).chr(0xc0).chr(0x21).chr(0x37).chr(0x00).chr(0x08).chr(0x04).chr(0x00).chr(0x22).chr(0x28).chr(0x23).chr(0x00);
    $this->zcl_read_attribute_status_record_0 = ReadAttributesStatusRecord::construct(0x0102, ZCLStatus::HARDWARE_FAILURE, 0x21, 55);
    $this->zcl_read_attribute_status_record_1 = ReadAttributesStatusRecord::construct(0x0408, ZCLStatus::SUCCESS, 0x22, 9000);
    $this->zcl_frame = ReadAttributesResponseCommand::construct([
        $this->zcl_read_attribute_status_record_0, $this->zcl_read_attribute_status_record_1
    ]);
    }

  public function testDisplayFrame()
    {
    $this->assertEquals('0x02 0x01 0xc0 0x21 0x37 0x00 0x08 0x04 0x00 0x22 0x28 0x23 0x00', $this->zcl_frame->displayFrame());
    }

  public function testReverse()
    {
    $old_zcl_frame = $this->zcl_frame->getFrame();
    $new = new ZCLFrame($old_zcl_frame);
    $this->assertEquals($old_zcl_frame, $new->getFrame());
    }

  public function testSetFrame()
    {
    $frame = new ReadAttributesResponseCommand($this->zcl_str);

    $records = $frame->getReadAttributesStatusRecords();
    $this->assertEquals(2, count($records));

    $this->assertEquals($this->zcl_read_attribute_status_record_0, $records[0]);
    $this->assertEquals($this->zcl_read_attribute_status_record_1, $records[1]);
    }
  }