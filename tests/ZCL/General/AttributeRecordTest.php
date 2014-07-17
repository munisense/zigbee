<?php

namespace Munisense\Zigbee\ZCL\General;

class AttributeRecordTest extends \PHPUnit_Framework_TestCase
  {
  private $frame;

  const ATTRIBUTE_ID = 0x0020;

  public function __construct()
    {
    $this->frame = chr(AttributeRecord::DIRECTION_SERVER_TO_CLIENT) . chr(0x20) . chr(0x00);
    }

  public function testConstructor()
    {
    $elem = new AttributeRecord($this->frame);
    $this->assertEquals(AttributeRecord::DIRECTION_SERVER_TO_CLIENT, $elem->getDirection());
    $this->assertEquals(self::ATTRIBUTE_ID, $elem->getAttributeId());
    }

  public function testConstruct()
    {
    $elem = AttributeRecord::construct(AttributeRecord::DIRECTION_SERVER_TO_CLIENT, self::ATTRIBUTE_ID);
    $this->assertEquals(AttributeRecord::DIRECTION_SERVER_TO_CLIENT, $elem->getDirection());
    $this->assertEquals(self::ATTRIBUTE_ID, $elem->getAttributeId());
    }

  public function testDisplay()
    {
    $elem = new AttributeRecord($this->frame);
    $this->assertEquals("0x00", $elem->displayDirection());
    $this->assertEquals("0x0020", $elem->displayAttributeId());
    $this->assertEquals("Direction: 0x00, AttributeId: 0x0020", $elem->__toString());
    $this->assertEquals("0x00 0x20 0x00", $elem->displayFrame());
    }

  public function testReverse()
    {
    $elem = AttributeRecord::construct(AttributeRecord::DIRECTION_SERVER_TO_CLIENT, self::ATTRIBUTE_ID);
    $old_frame = $elem->getFrame();
    $new_elem = new AttributeRecord($old_frame);
    $this->assertEquals($old_frame, $new_elem->getFrame());
    }
  }