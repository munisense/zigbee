<?php

namespace Munisense\Zigbee\ZDO;

class Status
  {
  const SUCCESS = 0x00;
  const INV_REQUESTTYPE = 0x80;
  const DEVICE_NOT_FOUND = 0x81;
  const INVALID_EP = 0x82;
  const NOT_ACTIVE = 0x83;
  const NOT_SUPPORTED = 0x84;
  const TIMEOUT = 0x85;
  const NO_MATCH = 0x86;
  const NO_ENTRY = 0x88;
  const NO_DESCRIPTOR = 0x89;
  const INSUFFICIENT_SPACE = 0x8A;
  const NOT_PERMITTED = 0x8B;
  const TABLE_FULL = 0x8C;
  const NOT_AUTHORIZED = 0x8D;

  public static $status = array(
     self::SUCCESS => "SUCCESS",
     self::INV_REQUESTTYPE => "INV_REQUESTTYPE",
     self::DEVICE_NOT_FOUND => "DEVICE_NOT_FOUND",
     self::INVALID_EP => "INVALID_EP",
     self::NOT_ACTIVE => "NOT_ACTIVE",
     self::NOT_SUPPORTED => "NOT_SUPPORTED",
     self::TIMEOUT => "TIMEOUT",
     self::NO_MATCH => "NO_MATCH",
     self::NO_ENTRY => "UNSUPPORTED_ATTRIBUTE",
     self::NO_DESCRIPTOR => "NO_DESCRIPTOR",
     self::INSUFFICIENT_SPACE => "INSUFFICIENT_SPACE",
     self::NOT_PERMITTED => "NOT_PERMITTED",
     self::TABLE_FULL => "TABLE_FULL",
     self::NOT_AUTHORIZED => "NOT_AUTHORIZED"
  );

  public static function displayStatus($status_id)
    {
    if(isset(self::$status[$status_id]))
      return self::$status[$status_id];
    else
      return "Unknown (".sprintf("0x%02x", $status_id).")";
    }
  }