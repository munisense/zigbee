<?php

namespace Munisense\Zigbee\ZCL\General;

use Munisense\Zigbee\ZCL\ZCLFrame;

class WriteAttributesCommandTest extends \PHPUnit_Framework_TestCase
  {
  private $zcl_str;
  /**
   * @var WriteAttributesCommand
   */
  private $zcl_frame;
  private $write_attribute_record_0;
  private $write_attribute_record_1;

  public function setUp()
    {
    $this->zcl_str = chr(0x02).chr(0x01).chr(0x21).chr(0x37).chr(0x00).chr(0x08).chr(0x04).chr(0x22).chr(0x28).chr(0x23).chr(0x00);
    $this->write_attribute_record_0 = WriteAttributeRecord::construct(0x0102, 0x21, 55);
    $this->write_attribute_record_1 = WriteAttributeRecord::construct(0x0408, 0x22, 9000);
    $this->zcl_frame = WriteAttributesCommand::construct([
        $this->write_attribute_record_0, $this->write_attribute_record_1
    ]);
    }

  public function testDisplayFrame()
    {
    $this->assertEquals('0x02 0x01 0x21 0x37 0x00 0x08 0x04 0x22 0x28 0x23 0x00', $this->zcl_frame->displayFrame());
    }

  public function testReverse()
    {
    $old_zcl_frame = $this->zcl_frame->getFrame();
    $new = new ZCLFrame($old_zcl_frame);
    $this->assertEquals($old_zcl_frame, $new->getFrame());
    }

  public function testSetFrame()
    {
    $frame = new WriteAttributesCommand($this->zcl_str);

    $records = $frame->getWriteAttributeRecords();
    $this->assertEquals(2, count($records));

    $this->assertEquals($this->write_attribute_record_0, $records[0]);
    $this->assertEquals($this->write_attribute_record_1, $records[1]);
    }
  }