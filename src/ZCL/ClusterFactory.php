<?php

namespace Munisense\Zigbee\ZCL;
use Munisense\Zigbee\Exception\ZigbeeException;

/**
 * Class ClusterFactory
 * @package Munisense\Zigbee\ZCL
 */
class ClusterFactory
  {
  /**
   * @param $cluster_id
   * @return ICluster
   * @throws ZigbeeException
   */
  public static function getClusterClassInstance($cluster_id)
    {
    if(isset(Cluster::$cluster[$cluster_id]))
      return new Cluster::$cluster[$cluster_id]['class']();
    else
      throw new ZigbeeException("Cluster ".sprintf("0x%04x", $cluster_id)." not found");
    }
  }