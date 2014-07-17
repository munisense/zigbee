<?php

namespace Munisense\Zigbee\ZCL\General;


class GeneralCommandTest extends \PHPUnit_Framework_TestCase
  {
  public function testDisplayCommand()
    {
    $this->assertEquals("Report Attributes", GeneralCommand::displayCommand(0x0a));
    $this->assertEquals("Unknown (0xff)", GeneralCommand::displayCommand(0xFF));
    }
  }
 