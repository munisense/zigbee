<?php

namespace Munisense\Zigbee\APS;

use Munisense\Zigbee\ZCL\General\ReadAttributesCommand;
use Munisense\Zigbee\ZCL\ZCLFrame;

class APSFrameTest extends \PHPUnit_Framework_TestCase
  {
  /**
   * This test ensures the default APS parameters do not change without notification
   */
  public function testAPSDefaults()
    {
    $frame = new APSFrame();
    $this->assertEquals("0x00 0x00 0x00 0x00 0x00 0x00 0x00 0x00", $frame->displayFrame());
    }

  /**
   * This test ensures the default APS parameters do not change without notification
   */
  public function testShortFrameDefaults()
    {
    $frame = new APSFrame();
    $frame->setFrameFormat(APSFrame::FRAME_FORMAT_SHORT_ZCL);
    $this->assertEquals("0x00 0x00 0x00", $frame->displayFrame());
    }

  public function testZCLInclusion()
    {
    $zcl_frame = ZCLFrame::construct(ReadAttributesCommand::construct([]));

    $aps_frame = new APSFrame();
    $aps_frame->setPayloadObject($zcl_frame);

    // The APS payload should be the same as the frame
    $this->assertEquals($zcl_frame->getFrame(), $aps_frame->getPayload());
    $this->assertEquals(APSFrame::FRAME_TYPE_DATA, $aps_frame->getFrameType());
    }

  public function testSetters()
    {
    $frame = new APSFrame();
    $frame->setDestinationEndpoint(0x0a);
    $frame->setClusterId(0x0001);
    $frame->setProfileId(0xf123);
    $frame->setSourceEndpoint(0x0b);
    $frame->setApsCounter(0xfe);
    $this->assertEquals("0x00 0x0a 0x01 0x00 0x23 0xf1 0x0b 0xfe", $frame->displayFrame());
    }

  /**
   * 2.2.5.1.2 This field shall be included in the frame only if the
   * delivery mode sub-field of the frame control field is set to 0b00 (normal unicast
   * delivery), 0b01 (indirect delivery where the indirect address mode sub-field of the
   * frame control field is also set to 0), or 0b10 (broadcast delivery).
   */
  public function testDestinationEndpoint()
    {
    $frame = new APSFrame();
    $frame->setDestinationEndpoint(0x0a);
    $frame->setGroupAddress(0xf00d);

    // Unicast, broadcast and indirect have the destination endpoint but not the group_id
    $frame->setDeliveryMode(APSFrame::DELIVERY_MODE_UNICAST);
    $this->assertEquals("0x00 0x0a 0x00 0x00 0x00 0x00 0x00 0x00", $frame->displayFrame());
    $frame->setDeliveryMode(APSFrame::DELIVERY_MODE_BROADCAST);
    $this->assertEquals("0x08 0x0a 0x00 0x00 0x00 0x00 0x00 0x00", $frame->displayFrame());
    $frame->setDeliveryMode(APSFrame::DELIVERY_MODE_INDIRECT);
    $this->assertEquals("0x04 0x0a 0x00 0x00 0x00 0x00 0x00 0x00", $frame->displayFrame());
    }

  public function testGroupId()
    {
    $frame = new APSFrame();
    $frame->setDestinationEndpoint(0x0a);
    $frame->setGroupAddress(0xf00d);

    // Group ID should be included, but not the destination
    $frame->setDeliveryMode(APSFrame::DELIVERY_MODE_GROUP_ADDRESS);
    $this->assertEquals("0x0c 0x0d 0xf0 0x00 0x00 0x00 0x00 0x00 0x00", $frame->displayFrame());
    }

  public function testReverse()
    {
    $input = chr(0x00) . chr(0x0a) . chr(0x01) . chr(0x00) . chr(0x23) . chr(0xf1) . chr(0x0b) . chr(0xfe);
    $frame = new APSFrame($input);
    $this->assertEquals($input, $frame->getFrame());
    }
  }