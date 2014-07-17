<?php

namespace Munisense\Zigbee\ZCL;

use Munisense\Zigbee\Exception\MuniZigbeeException;
use Munisense\Zigbee\ZCL\General\GeneralCommand;
use Munisense\Zigbee\ZCL\General\AttributeIdentifier;
use Munisense\Zigbee\ZCL\General\ReadAttributesCommand;

class ZigbeeZCLFrameTest extends \PHPUnit_Framework_TestCase
  {
  public function testReadAttribute()
    {
    $zcl = new ZCLFrame();
    $zcl->setTransactionId(0x0001);

    $read_attributes_elem = new AttributeIdentifier();
    $read_attributes_elem->setAttributeId(0x02);

    $read_attributes = new ReadAttributesCommand();
    $read_attributes->addAttributeIdentifier($read_attributes_elem);

    $zcl->setPayloadObject($read_attributes);

    $this->assertEquals('0x00 0x01 0x00 0x02 0x00', $zcl->displayFrame());
    }

  public function testReadAttributeStaticConstruct()
    {
    $zcl = ZCLFrame::construct(
        ReadAttributesCommand::construct([
            AttributeIdentifier::construct(0x02)
        ]),
        0x4E47,
        ZCLFrame::DIRECTION_SERVER_TO_CLIENT,
        ZCLFrame::DEFAULT_RESPONSE_ENABLED,
        0x0001
    );

    $this->assertEquals('0x04 0x47 0x4e 0x01 0x00 0x02 0x00', $zcl->displayFrame());
    }

  public function testUpdateFrameType()
    {
    $zcl = ZCLFrame::construct();
    $zcl->setCommandId(GeneralCommand::CONFIGURE_REPORTING);
    $zcl->setFrameType(ZCLFrame::FRAME_TYPE_CLUSTER_SPECIFIC);

    // Set payload changes the FrameType
    $zcl->setPayloadObject(ReadAttributesCommand::construct([]));

    $this->assertEquals(ZCLFrame::FRAME_TYPE_PROFILE_WIDE, $zcl->getFrameType());
    $this->assertEquals(GeneralCommand::READ_ATTRIBUTES, $zcl->getCommandId());
    }

  public function testReverse()
    {
    $old_zcl = ZCLFrame::construct(
        ReadAttributesCommand::construct([
            AttributeIdentifier::construct(0x02)
        ]),
        0x4E47,
        ZCLFrame::DIRECTION_SERVER_TO_CLIENT,
        ZCLFrame::DEFAULT_RESPONSE_ENABLED,
        0x0001
    );

    $old_zcl_frame = $old_zcl->getFrame();

    $new = new ZCLFrame($old_zcl_frame);

    $this->assertEquals($old_zcl_frame, $new->getFrame());
    }
  }