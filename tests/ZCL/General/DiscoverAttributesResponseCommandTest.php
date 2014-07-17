<?php

namespace Munisense\Zigbee\ZCL\General;

use Munisense\Zigbee\ZCL\ZCLFrame;

class DiscoverAttributesResponseCommandTest extends \PHPUnit_Framework_TestCase
  {
  private $zcl_str;
  /**
   * @var DiscoverAttributesResponseCommand
   */
  private $zcl_frame;
  private $zcl_attributes_element_0;
  private $zcl_attributes_element_1;

  public function setUp()
    {
    $this->zcl_str = chr(0x01).chr(0x02).chr(0x01).chr(0x21).chr(0x08).chr(0x04).chr(0x22);
    $this->zcl_attributes_element_0 = AttributeInformation::construct(0x0102, 0x21);
    $this->zcl_attributes_element_1 = AttributeInformation::construct(0x0408, 0x22);
    $this->zcl_frame = DiscoverAttributesResponseCommand::construct(true, [
        $this->zcl_attributes_element_0, $this->zcl_attributes_element_1
    ]);
    }

  public function testDisplayFrame()
    {
    $this->assertEquals('0x01 0x02 0x01 0x21 0x08 0x04 0x22', $this->zcl_frame->displayFrame());
    }

  public function testReverse()
    {
    $old_zcl_frame = $this->zcl_frame->getFrame();
    $new = new ZCLFrame($old_zcl_frame);
    $this->assertEquals($old_zcl_frame, $new->getFrame());
    }

  public function testSetFrame()
    {
    $frame = new DiscoverAttributesResponseCommand($this->zcl_str);

    $records = $frame->getAttributes();
    $this->assertEquals(2, count($records));

    $this->assertEquals($this->zcl_attributes_element_0, $records[0]);
    $this->assertEquals($this->zcl_attributes_element_1, $records[1]);
    }
  }