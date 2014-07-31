<?php

namespace Munisense\Zigbee\ZDO;


use Munisense\Zigbee\ZDO\Discovery\NwkAddrReqCommand;

class ZDOFrameTest extends \PHPUnit_Framework_TestCase
  {
  /**
   * This test ensures the default ZDO parameters do not change without notification
   */
  public function testZDODefaults()
    {
    $frame = new ZDOFrame();
    $this->assertEquals("0x00", $frame->displayFrame());
    }

  public function testCommandInclusion()
    {
    $command = NwkAddrReqCommand::constructSingle(0xbeef);
    $frame = ZDOFrame::construct($command, 0x12);

    $this->assertEquals($command->getClusterId(), $frame->getCommandId());
    $this->assertEquals("0x12 0xef 0xbe 0x00 0x00 0x00 0x00 0x00 0x00 0x00", $frame->displayFrame());
    }

  public function testGetPayloadObject()
    {
    $command = NwkAddrReqCommand::constructSingle(0xbeef);
    $frame = ZDOFrame::construct($command, 0x12);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDO\\Discovery\\NwkAddrReqCommand", $frame->getPayloadObject());
    }
  }