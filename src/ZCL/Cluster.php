<?php

namespace Munisense\Zigbee\ZCL;
use Munisense\Zigbee\Exception\MuniZigbeeException;
use Munisense\Zigbee\ZCL\IAS_Zone\IAS_Zone;

/**
 * Class Cluster
 * @package Munisense\Zigbee\ZCL
 *
 * (Incomplete) List of clusters
 */
class Cluster
  {
  /**
   * The security and safety functional domaincontains clusters and information to
   * build devices in the security and safety domain, e.g. alarm units.
   */
  const IAS_Zone = IAS_Zone::CLUSTER_ID; // Attributes and commands for IAS security zone devices
  const IAS_ACE = 0x0501; // Attributes and commands for IAS Ancillary Control Equipment
  const IAS_WD = 0x0502; // Attributes and commands for IAS Warning Devices

  public static $cluster = array(
    self::IAS_Zone => array("class" => "Munisense\\Zigbee\\ZCL\\IAS_Zone\\IAS_Zone", "name" => IAS_Zone::NAME),
    self::IAS_ACE => array("class" => "Munisense\\Zigbee\\ZCL\\IAS_ACE\\IAS_ACE", "name" => "IAS Ancillary Control Equipment"),
    self::IAS_WD => array("class" => "Munisense\\Zigbee\\ZCL\\IAS_WD\\IAS_WD", "name" => "IAS Warning Devices")
  );

  /**
   * @param $cluster_id
   * @return ICluster
   * @throws MuniZigbeeException
   */
  public static function getClusterClassInstance($cluster_id)
    {
    if(isset(self::$cluster[$cluster_id]))
      return new self::$cluster[$cluster_id]['class']();
    else
      throw new MuniZigbeeException("Cluster ".$cluster_id." not found");
    }
  }