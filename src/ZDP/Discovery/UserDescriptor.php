<?php

namespace Munisense\Zigbee\ZDP\Discovery;

use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\ZigbeeException;

/**
 * Class UserDescriptor
 * @package Munisense\Zigbee\ZDP\Discovery
 *
 * The user descriptor containsinformation that allows the user to identify the device
 * using a user-friendly character string, such as 'Bedroom TV' or 'Stairs light'.
 * The use of the user descriptor is optional.
 */
class UserDescriptor extends AbstractFrame
  {
  private $user_description = "";

  public static function construct($user_description)
    {
    $frame = new self;
    $frame->setUserDescription($user_description);
    return $frame;
    }

  /**
   * Returns the frame as a sequence of bytes.
   *
   * @return string $frame
   */
  function getFrame()
    {
    $frame = "";

    // Note: 2.3.2.7 Suggests 16 bytes always, but the length field of the UserDescRsp command would then not be needed?
    $user_description = $this->getUserDescription();
    for($x = 0; $x < strlen($user_description); $x++)
      if(isset($user_description[$x]))
        Buffer::packInt8u($frame, ord($user_description[$x]));

    return $frame;
    }

  /**
   * @param string $frame
   */
  function setFrame($frame)
    {
    $this->setUserDescription(substr($frame, 0, 16));
    }

  /**
   * @param string $user_description Description of maximum 16 bytes long
   * @throws ZigbeeException When the input is too long
   */
  public function setUserDescription($user_description)
    {
    if(strlen($user_description) <= 16)
      $this->user_description = $user_description;
    else
      throw new ZigbeeException("User Description may not be longer than 16 bytes: " .strlen($user_description));
    }

  /**
   * @return string
   */
  public function getUserDescription()
    {
    return $this->user_description;
    }

  /**
   * @return string
   */
  public function displayUserDescription()
    {
    return $this->user_description;
    }
  public function __toString()
    {
    $output = __CLASS__." (length: ".strlen($this->getFrame()).")".PHP_EOL;
    $output .= " `- Description: ".$this->displayUserDescription();
    return $output;
    }
  }