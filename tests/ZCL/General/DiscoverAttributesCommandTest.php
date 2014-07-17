<?php

namespace Munisense\Zigbee\ZCL\General;

use Munisense\Zigbee\ZCL\ZCLFrame;

class DiscoverAttributesCommandTest extends \PHPUnit_Framework_TestCase
  {
  /**
   * @var DiscoverAttributesCommand
   */
  private $frame;

  const START_ATTRIBUTE_IDENTIFIER = 0x0000;
  const MAXIMUM_ATTRIBUTE_IDENTIFIERS = 32;

  public function setUp()
    {
    $this->frame = DiscoverAttributesCommand::construct(self::START_ATTRIBUTE_IDENTIFIER, self::MAXIMUM_ATTRIBUTE_IDENTIFIERS);
    }

  public function testStaticConstruct()
    {
    $this->assertEquals('0x00 0x00 0x20', $this->frame->displayFrame());
    }

  public function testZCLInclusion()
    {
    $zcl = new ZCLFrame();

    try
      {
      $zcl->setPayloadObject($this->frame);
      }
    catch(\Exception $e)
      {
      $this->fail("Could not include DiscoverAttributesCommand in a ZCLFrame: ".$e->getMessage());
      }
    }

  public function testReverse()
    {
    $old_zcl_frame = $this->frame->getFrame();
    $new = new DiscoverAttributesCommand($old_zcl_frame);
    $this->assertEquals($old_zcl_frame, $new->getFrame());
    }
  }