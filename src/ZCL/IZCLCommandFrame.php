<?php

namespace Munisense\Zigbee\ZCL;

use Munisense\Zigbee\IFrame;

interface IZCLCommandFrame extends IFrame
  {
  /**
   * Returns the Command ID of this frame
   * @return int
   */
  public function getCommandId();

  /**
   * Returns the Frame Type of this frame
   * @return int
   */
  public function getFrameType();
  }