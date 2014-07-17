<?php

namespace Munisense\Zigbee\ZCL;

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
  const IAS_Zone = 0x0500; // Attributes and commands for IAS security zone devices
  const IAS_ACE = 0x0501; // Attributes and commands for IAS Ancillary Control Equipment
  const IAS_WD = 0x0502; // Attributes and commands for IAS Warning Devices
  }