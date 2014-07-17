<?php

namespace Munisense\Zigbee\ZCL;

use Munisense\Zigbee\ZCL\ZCLStatus;

class ZCLStatusTest extends \PHPUnit_Framework_TestCase
  {
  public function testDisplayCommand()
    {
    $this->assertEquals("UNSUPPORTED_ATTRIBUTE", ZCLStatus::displayStatus(0x86));
    $this->assertEquals("Unknown (0xff)", ZCLStatus::displayStatus(0xFF));
    }
  }
 