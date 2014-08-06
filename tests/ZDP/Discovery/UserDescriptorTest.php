<?php

namespace Munisense\Zigbee\ZDP\Discovery;

class UserDescriptorTest extends \PHPUnit_Framework_TestCase
  {
  public function testReverse()
    {
    $user_desc = UserDescriptor::construct("this is a test");
    $frame = $user_desc->getFrame();

    $new_user_desc = new UserDescriptor($frame);
    $this->assertEquals($user_desc->displayFrame(), $new_user_desc->displayFrame());
    }

  /**
   * @expectedException \Munisense\Zigbee\Exception\ZigbeeException
   */
  public function testLengthException()
    {
    UserDescriptor::construct("this is a test with a way too long name so it will throw an error");
    }

  public function testFrameLength()
    {
    $str = "short";
    $user_desc = UserDescriptor::construct($str);
    $this->assertEquals(strlen($str), strlen($user_desc->getFrame()));
    }
  }
 