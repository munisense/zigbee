<?php

namespace Munisense\Zigbee\ZCL;

use Munisense\Zigbee\Exception\ZigbeeException;

class ClusterBase
  {
  const NAME = null;
  const CLUSTER_ID = null;

  protected static $command = [];
  protected static $attribute = [];

  public function getName()
    {
    return static::NAME;
    }

  public function getClusterId()
    {
    return static::CLUSTER_ID;
    }

  public static function getClusterSpecificCommands()
    {
    return static::$command;
    }


  public function getClusterSpecificCommand($command_id)
    {
    if(!isset(static::$command[$command_id]))
      throw new ZigbeeException("Cluster specific command ".$command_id." not found in ".__CLASS__);

    return static::$command[$command_id];
    }

  public static function displayClusterSpecificCommand($command_id)
    {
    if(!isset(static::$command[$command_id]))
      return "Unknown (".sprintf("0x%02x", $command_id).")";

    return static::$command[$command_id]['name'];
    }

  public static function getAttributes()
    {
    return static::$attribute;
    }

  public static function getAttribute($attribute_id)
    {
    if(!isset(static::$attribute[$attribute_id]))
      throw new ZigbeeException("Attribute ".$attribute_id." not found in ".__CLASS__);

    return static::$attribute[$attribute_id];
    }

  public static function displayAttribute($attribute_id)
    {
    if(!isset(static::$attribute[$attribute_id]))
      return "Unknown (".sprintf("0x%04x", $attribute_id).")";

    return static::$attribute[$attribute_id]['name'];
    }
  }