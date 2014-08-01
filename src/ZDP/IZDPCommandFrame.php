<?php
namespace Munisense\Zigbee\ZDP;

use Munisense\Zigbee\IFrame;

interface IZDPCommandFrame extends IFrame
  {
  /**
   * Returns the Cluster ID of this frame
   * @return int
   */
  public function getClusterId();
  }