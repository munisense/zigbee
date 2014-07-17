<?php

namespace Munisense\Zigbee\ZCL\IAS_Zone;

class ClusterSpecificCommand
  {
  const ZONE_STATUS_CHANGE_NOTIFICATION = 0x00;
  const ZONE_ENROLL_REQUEST = 0x01;

  public static $command = array(
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
  }