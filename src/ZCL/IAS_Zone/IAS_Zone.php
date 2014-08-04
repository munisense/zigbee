<?php

namespace Munisense\Zigbee\ZCL\IAS_Zone;

use Munisense\Zigbee\ZCL\ClusterBase;

class IAS_Zone extends ClusterBase
  {
  const CLUSTER_ID = 0x0500;
  const NAME = "IAS Zone";

  const ZONE_STATE_ATTRIBUTE = 0x0000;
  const ZONE_TYPE_ATTRIBUTE = 0x0001;
  const ZONE_STATUS_ATTRIBUTE = 0x0002;
  const IAS_CIE_ADDRESS_ATTRIBUTE = 0x0010;

  public static $attribute = [
    self::ZONE_STATE_ATTRIBUTE => ["name" => "ZoneState", "description" => "", "datatype_id" => 0x30],
    self::ZONE_TYPE_ATTRIBUTE => ["name" => "ZoneType", "description" => "", "datatype_id" => 0x31],
    self::ZONE_STATUS_ATTRIBUTE => ["name" => "ZoneStatus", "description" => "", "datatype_id" => 0x19],
    self::IAS_CIE_ADDRESS_ATTRIBUTE => ["name" => "IasCieAddress", "description" => "", "datatype_id" => 0xf0],
  ];

  const ZONE_STATUS_CHANGE_NOTIFICATION_COMMAND = ZoneStatusChangeNotificationCommand::COMMAND_ID;

  protected static $command = array(
      ZoneStatusChangeNotificationCommand::COMMAND_ID => 'Munisense\Zigbee\ZCL\IAS_Zone\ZoneStatusChangeNotificationCommand',
  );
  }