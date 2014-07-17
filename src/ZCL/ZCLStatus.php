<?php

namespace Munisense\Zigbee\ZCL;

class ZCLStatus
  {
  const SUCCESS = 0x00;
  const FAILURE = 0x01;
  const MALFORMED_COMMAND = 0x80;
  const UNSUP_CLUSTER_COMMAND = 0x81;
  const UNSUP_GENERAL_COMMAND = 0x82;
  const UNSUP_MANUF_CLUSTER_COMMAND = 0x83;
  const UNSUP_MANUF_GENERAL_COMMAND = 0x84;
  const INVALID_FIELD = 0x85;
  const UNSUPPORTED_ATTRIBUTE = 0x86;
  const INVALID_VALUE = 0x87;
  const READ_ONLY = 0x88;
  const INSUFFICIENT_SPACE = 0x89;
  const DUPLICATE_EXIST = 0x8A;
  const NOT_FOUND = 0x08B;
  const UNREPORTABLE_ATTRIBUTE = 0x8C;
  const INVALID_DATA_TYPE = 0x8D;
  const HARDWARE_FAILURE = 0xC0;
  const SOFTWARE_FAILURE = 0xC1;
  const CALIBRATION_ERROR = 0xC2;

  public static $status = array(
     self::SUCCESS => "SUCCESS",
     self::FAILURE => "FAILURE",
     self::MALFORMED_COMMAND => "MALFORMED_COMMAND",
     self::UNSUP_CLUSTER_COMMAND => "UNSUP_CLUSTER_COMMAND",
     self::UNSUP_GENERAL_COMMAND => "UNSUP_GENERAL_COMMAND",
     self::UNSUP_MANUF_CLUSTER_COMMAND => "UNSUP_MANUF_CLUSTER_COMMAND",
     self::UNSUP_MANUF_GENERAL_COMMAND => "UNSUP_MANUF_GENERAL_COMMAND",
     self::INVALID_FIELD => "INVALID_FIELD",
     self::UNSUPPORTED_ATTRIBUTE => "UNSUPPORTED_ATTRIBUTE",
     self::INVALID_VALUE => "INVALID_VALUE",
     self::READ_ONLY => "READ_ONLY",
     self::INSUFFICIENT_SPACE => "INSUFFICIENT_SPACE",
     self::DUPLICATE_EXIST => "DUPLICATE_EXIST",
     self::NOT_FOUND => "NOT_FOUND",
     self::UNREPORTABLE_ATTRIBUTE => "UNREPORTABLE_ATTRIBUTE",
     self::INVALID_DATA_TYPE => "INVALID_DATA_TYPE",
     self::HARDWARE_FAILURE => "HARDWARE_FAILURE",
     self::SOFTWARE_FAILURE => "SOFTWARE_FAILURE",
     self::CALIBRATION_ERROR => "CALIBRATION_ERROR",
  );

  public static function displayStatus($status_id)
    {
    if(isset(self::$status[$status_id]))
      return self::$status[$status_id];
    else
      return "Unknown (".sprintf("0x%02x", $status_id).")";
    }
  }