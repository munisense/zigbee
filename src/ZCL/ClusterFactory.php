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
   * @return AbstractCluster
   * @throws ZigbeeException
   */
  public static function getClusterClassInstance($cluster_id)
    {
    if(!isset(Cluster::$cluster[$cluster_id]))
      throw new ZigbeeException("Cluster ".sprintf("0x%04x", $cluster_id)." not found");

    return new Cluster::$cluster[$cluster_id]();
    }
  }