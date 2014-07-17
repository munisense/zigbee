<?php

namespace Munisense\Zigbee\ZCL\General;

class AttributeInformationTest extends \PHPUnit_Framework_TestCase
  {
  public function testConstructor()
    {
    $element = new AttributeInformation(chr(0x01).chr(0x00).chr(0x20));
    $this->assertEquals(0x0001, $element->getAttributeId());
    $this->assertEquals(0x20, $element->getDatatypeId());
    }

  public function testToString()
    {
    $this->assertEquals("AttributeId: 0x0001, DatatypeId: 0x20",AttributeInformation::construct(0x0001, 0x20)->__toString());
    }
  }