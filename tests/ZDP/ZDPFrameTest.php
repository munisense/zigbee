<?php

namespace Munisense\Zigbee\ZDP;


use Munisense\Zigbee\ZDP\Discovery\NwkAddrReqCommand;

class ZDPFrameTest extends \PHPUnit_Framework_TestCase
  {
  /**
   * This test ensures the default ZDP parameters do not change without notification
   */
  public function testZDPDefaults()
    {
    $frame = new ZDPFrame();
    $this->assertEquals("0x00", $frame->displayFrame());
    }

  public function testFindClassByCluster()
    {
    $this->assertEquals("Munisense\\Zigbee\\ZDP\\Discovery\\IEEEAddrRspCommand", ZDPFrame::findClassByCluster(0x8001));
    }

  public function testCommandInclusion()
    {
    $command = NwkAddrReqCommand::constructSingle(0xbeef);
    $frame = ZDPFrame::construct($command, 0x12);

    $this->assertEquals($command->getClusterId(), $frame->getCommandId());
    $this->assertEquals("0x12 0xef 0xbe 0x00 0x00 0x00 0x00 0x00 0x00 0x00 0x00", $frame->displayFrame());
    }

  public function testGetPayloadObject()
    {
    $command = NwkAddrReqCommand::constructSingle(0xbeef);
    $frame = ZDPFrame::construct($command, 0x12);
    $this->assertInstanceOf("Munisense\\Zigbee\\ZDP\\Discovery\\NwkAddrReqCommand", $frame->getPayloadObject());
    }
  }