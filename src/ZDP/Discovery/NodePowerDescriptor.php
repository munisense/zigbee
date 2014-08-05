<?php

namespace Munisense\Zigbee\ZDP\Discovery;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\ZigbeeException;

/**
 * Class NodePowerDescriptor
 *
 * @package Munisense\Zigbee\ZDP\Discovery
 *
 * The node power descriptor gives a dynamic indication of the power status of the
 * node and is mandatory for each node. There shall be only one node power
 * descriptor in a node
 */
class NodePowerDescriptor extends AbstractFrame
  {
  private $current_power_mode = 0;
  const MODE_RECEIVER_SYNCHRONIZED = 0b0000;
  const MODE_RECEIVER_PERIODICALLY = 0b0001;
  const MODE_RECEIVER_STIMULATED   = 0b0010;

  private $available_power_sources = 0;
  private $current_power_source = 0;
  const SOURCE_CONSTANT_MAINS_POWER = 0b0001;
  const SOURCE_RECHARGEABLE_BATTERY = 0b0010;
  const SOURCE_DISPOSABLE_BATTERY   = 0b0100;

  private $current_power_level = 0;
  const LEVEL_CRITICAL = 0b0000;
  const LEVEL_33PERC   = 0b0100;
  const LEVEL_66PERC   = 0b1000;
  const LEVEL_100PERC  = 0b1100;

  public static function construct($current_power_mode, $available_power_sources, $current_power_source, $current_power_level)
    {
    $frame = new self;
    $frame->setCurrentPowerMode($current_power_mode);
    $frame->setAvailablePowerSources($available_power_sources);
    $frame->setCurrentPowerSource($current_power_source);
    $frame->setCurrentPowerLevel($current_power_level);
    return $frame;
    }

  /**
   * Returns the frame as a sequence of bytes.
   *
   * @return string $frame
   */
  function getFrame()
    {
    $frame = "";

    $byte1 = $this->getCurrentPowerMode() & 0x0F;
    $byte1 |= ($this->getAvailablePowerSources() << 4) & 0xF0;
    Buffer::packInt8u($frame, $byte1);

    $byte2 = $this->getCurrentPowerSource() & 0x0F;
    $byte2 |= ($this->getCurrentPowerLevel() << 4) & 0xF0;
    Buffer::packInt8u($frame, $byte2);

    return $frame;
    }

  /**
   * @param string $frame
   */
  function setFrame($frame)
    {
    $byte1 = Buffer::unpackInt8u($frame);
    $byte2 = Buffer::unpackInt8u($frame);

    $this->setCurrentPowerMode(($byte1 & 0x0F) >> 0);
    $this->setAvailablePowerSources(($byte1 & 0xF0) >> 4);
    $this->setCurrentPowerSource(($byte2 & 0x0F) >> 0);
    $this->setCurrentPowerLevel(($byte2 & 0xF0) >> 4);
    }

  /**
   * @return int Bitmap of available power sources
   */
  public function getAvailablePowerSources()
    {
    return $this->available_power_sources;
    }

  /**
   * @param int $available_power_sources Bitmap of available power sources
   * @throws ZigbeeException
   */
  public function setAvailablePowerSources($available_power_sources)
    {
    if($available_power_sources <= 0b0111)
      $this->available_power_sources = $available_power_sources;
    else
      throw new ZigbeeException("Invalid Available Power Sources");
    }

  /**
   * @return string
   */
  public function displayAvailablePowerSources()
    {
    return $this->displayPowerSource($this->getAvailablePowerSources());
    }

  /**
   * @return int Returns one of the LEVEL_* constants
   */
  public function getCurrentPowerLevel()
    {
    return $this->current_power_level;
    }

  /**
   * @param int $current_power_level Use the LEVEL_* constants
   * @throws ZigbeeException
   */
  public function setCurrentPowerLevel($current_power_level)
    {
    if(in_array($current_power_level, [self::LEVEL_100PERC, self::LEVEL_33PERC, self::LEVEL_66PERC, self::LEVEL_CRITICAL]))
      $this->current_power_level = $current_power_level;
    else
      throw new ZigbeeException("Invalid Current Power Level");
    }

  /**
   * @return string
   * @throws ZigbeeException
   */
  public function displayCurrentPowerLevel()
    {
    if(($this->getCurrentPowerLevel() & self::LEVEL_CRITICAL) === self::LEVEL_CRITICAL)
      return "Critical";

    if(($this->getCurrentPowerLevel() & self::LEVEL_66PERC) === self::LEVEL_66PERC)
      return "66%";

    if(($this->getCurrentPowerLevel() & self::LEVEL_33PERC) === self::LEVEL_33PERC)
      return "33%";

    if(($this->getCurrentPowerLevel() & self::LEVEL_100PERC) === self::LEVEL_100PERC)
      return "100%";

    throw new ZigbeeException("Invalid Power Level");
    }

  /**
   * @return int Returns one of the MODE_* constants
   */
  public function getCurrentPowerMode()
    {
    return $this->current_power_mode;
    }

  /**
   * @param int $current_power_mode Use the MODE_* constants
   * @throws ZigbeeException
   */
  public function setCurrentPowerMode($current_power_mode)
    {
    if(in_array($current_power_mode, [self::MODE_RECEIVER_PERIODICALLY, self::MODE_RECEIVER_STIMULATED, self::MODE_RECEIVER_SYNCHRONIZED]))
      $this->current_power_mode = $current_power_mode;
    else
      throw new ZigbeeException("Invalid Current Power Mode");
    }

  /**
   * @return string
   * @throws ZigbeeException
   */
  public function displayCurrentPowerMode()
    {
    if(($this->getCurrentPowerMode() & self::MODE_RECEIVER_SYNCHRONIZED) === self::MODE_RECEIVER_SYNCHRONIZED)
      return "RECEIVER_SYNCHRONIZED";

    if(($this->getCurrentPowerMode() & self::MODE_RECEIVER_STIMULATED) === self::MODE_RECEIVER_STIMULATED)
      return "RECEIVER_STIMULATED";

    if(($this->getCurrentPowerMode() & self::MODE_RECEIVER_PERIODICALLY) === self::MODE_RECEIVER_PERIODICALLY)
      return "RECEIVER_PERIODICALLY";

    throw new ZigbeeException("Invalid Power Mode");
    }

  /**
   * @return int Returns one of the SOURCE_* constants
   */
  public function getCurrentPowerSource()
    {
    return $this->current_power_source;
    }

  /**
   * @param int $current_power_source Use the SOURCE_* constants
   * @throws ZigbeeException
   */
  public function setCurrentPowerSource($current_power_source)
    {
    if(in_array($current_power_source, [self::SOURCE_CONSTANT_MAINS_POWER, self::SOURCE_DISPOSABLE_BATTERY, self::SOURCE_RECHARGEABLE_BATTERY]))
      $this->current_power_source = $current_power_source;
    else
      throw new ZigbeeException("Invalid Current Power Source");
    }

  /**
   * @return string
   */
  public function displayCurrentPowerSource()
    {
    return $this->displayPowerSource($this->getCurrentPowerSource());
    }

  /**
   * @param $power_source int Bitmap of power sources
   *
   * @return string Comma separated list of power sources included in the bitmap
   */
  public function displayPowerSource($power_source)
    {
    $sources = [];

    if(($power_source & self::SOURCE_RECHARGEABLE_BATTERY) === self::SOURCE_RECHARGEABLE_BATTERY)
      $sources[] = "RECHARGEABLE_BATTERY";

    if(($power_source & self::SOURCE_DISPOSABLE_BATTERY) === self::SOURCE_DISPOSABLE_BATTERY)
      $sources[] = "DISPOSABLE_BATTERY";

    if(($power_source & self::SOURCE_CONSTANT_MAINS_POWER) === self::SOURCE_CONSTANT_MAINS_POWER)
      $sources[] = "CONSTANT_MAINS_POWER";

    return implode(", ", $sources);
    }

  public function __toString()
    {
    $output = __CLASS__." (length: ".strlen($this->getFrame()).")".PHP_EOL;
    $output .= "|- Current Power Mode      : ".$this->displayCurrentPowerMode().PHP_EOL;
    $output .= "|- Available Power Sources : ".$this->displayAvailablePowerSources().PHP_EOL;
    $output .= "|- Current Power Source    : ".$this->displayCurrentPowerSource().PHP_EOL;
    $output .= "`- Current Power Level     : ".$this->displayCurrentPowerLevel().PHP_EOL;

    return $output;
    }
  }