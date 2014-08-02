<?php

namespace Munisense\Zigbee\ZCL\IAS_Zone;

use Munisense\Zigbee\Exception\MuniZigbeeException;
use Munisense\Zigbee\ZCL\ICluster;

class IAS_Zone implements ICluster
  {
  const CLUSTER_ID = 0x0500;
  const NAME = "IAS security zone devices";

  const ZONE_STATUS_CHANGE_NOTIFICATION = 0x00;
  const ZONE_ENROLL_REQUEST = 0x01;

  protected static $command = array(
      self::ZONE_STATUS_CHANGE_NOTIFICATION => array("class" => "Munisense\\Zigbee\\ZCL\\IAS_Zone\\ZoneStatusChangeNotificationCommand", "name" => "Zone Status Change Notification"),
      self::ZONE_ENROLL_REQUEST => array("class" => "Munisense\\Zigbee\\ZCL\\IAS_Zone\\ZoneEnrollRequestCommand", "name" => "Zone Enroll Request"),
  );

  public static function displayCommand($command_id)
    {
    if(isset(self::$command[$command_id]))
      return self::$command[$command_id]['name'];
    else
      return "Unknown (".sprintf("0x%02x", $command_id).")";
    }

  public function getName()
    {
    return self::NAME;
    }

  public function getClusterId()
    {
    return self::CLUSTER_ID;
    }

  public function getClusterSpecificCommands()
    {
    return self::$command;
    }

  public function getClusterSpecificCommand($command_id)
    {
    if(isset(self::$command[$command_id]))
      return self::$command[$command_id];
    else
      throw new MuniZigbeeException("Cluster specific command ".$command_id." not found in ".__CLASS__);
    }
  }