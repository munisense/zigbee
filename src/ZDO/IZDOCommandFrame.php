<?php
namespace Munisense\Zigbee\ZDO;

use Munisense\Zigbee\IFrame;

interface IZDOCommandFrame extends IFrame
  {
  /**
   * Returns the Cluster ID of this frame
   * @return int
   */
  public function getClusterId();
  }