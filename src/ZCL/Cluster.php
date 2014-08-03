<?php

namespace Munisense\Zigbee\ZCL;
use Munisense\Zigbee\ZCL\IAS_Zone\IAS_Zone;

/**
 * Class Cluster
 * @package Munisense\Zigbee\ZCL
 *
 * When additional cluster frames are implemented, they should be included in this enum / lookup table.
 */
class Cluster
  {
  /**
   * The security and safety functional domaincontains clusters and information to
   * build devices in the security and safety domain, e.g. alarm units.
   */
  const IAS_ZONE = IAS_Zone::CLUSTER_ID; // Attributes and commands for IAS security zone devices

  public static $cluster = array(
    self::IAS_ZONE => array("class" => 'Munisense\Zigbee\ZCL\IAS_Zone\IAS_Zone', "name" => IAS_ZONE::NAME)
  );
  }