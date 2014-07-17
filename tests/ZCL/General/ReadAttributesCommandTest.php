<?php

namespace Munisense\Zigbee\ZCL\General;

class ReadAttributesCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testReadAttributeStaticConstruct()
    {
    $zcl = ReadAttributesCommand::construct([
      AttributeIdentifier::construct(0x02),
      AttributeIdentifier::construct(0x0809)
    ]);

    $this->assertEquals('0x02 0x00 0x09 0x08', $zcl->displayFrame());
    }

  public function testReverse()
    {
    $old_zcl = ReadAttributesCommand::construct([
        AttributeIdentifier::construct(0x02),
        AttributeIdentifier::construct(0x0809)
    ]);

    $old_zcl_frame = $old_zcl->getFrame();

    $new = new ReadAttributesCommand($old_zcl_frame);

    $this->assertEquals($old_zcl_frame, $new->getFrame());
    }
  }