<?php

/**
 * 8.2.2.2.1.3  ZoneStatusAttribute
 *
 * The ZoneStatusattribute is a bit map. The meaning of each bit is summarized in Table 8.6.
 */
namespace Munisense\Zigbee\ZCL\IAS_Zone;

class ZoneStatus
  {
  protected $value;

  const ALARM_1_BITMASK = 0x0001;
  const ALARM_1_OPENED_OR_ALARMED = 1;
  const ALARM_1_CLOSED_OR_NOT_ALARMED = 0;

  const ALARM_2_BITMASK = 0x0002;
  const ALARM_2_OPENED_OR_ALARMED = 1;
  const ALARM_2_CLOSED_OR_NOT_ALARMED = 0;

  const TAMPER_BITMASK = 0x0004;
  const TAMPER_TAMPERED = 1;
  const TAMPER_NOT_TAMPERED = 0;

  const BATTERY_BITMASK = 0x0008;
  const BATTERY_LOW_BATTERY = 1;
  const BATTERY_OK = 0;

  /**
   * This bit indicates whether the Zone issues periodic Zone Status Change
   * Notification commands. The CIE device may use these periodic reports as an
   * indication that a zone is operational. Zones that do not implement the periodic
   * reporting are required to set this bit to zero (the CIE will know not to interpret the
   * lack of reports as a problem).
   */
  const SUPERVISION_REPORTS_BITMASK = 0x0010;
  const SUPERVISION_REPORTS_ON = 1;
  const SUPERVISION_REPORTS_DOES_NOT_REPORT = 0;

  /**
   * This bit indicates whether or not a Zone Status Change Notification
   * command will be sent to indicate that an alarm is no longer present. Some Zones
   * do not have the ability to detect that alarm condition is no longer present, they
   * only can tell that an alarm has occurred. These Zones must set the "Restore" bit to
   * zero, indicating to the CIE not to look for alarm-restore notifications.
   */
  const RESTORE_REPORTS_BITMASK = 0x0020;
  const RESTORE_REPORTS_ON = 1;
  const RESTORE_REPORTS_DOES_NOT_REPORT_RESTORE = 0;

  const TROUBLE_BITMASK = 0x0040;
  const TROUBLE_FAILURE = 1;
  const TROUBLE_OK = 0;

  const AC_MAINS_BITMASK = 0x0080;
  const AC_MAINS_FAULT = 1;
  const AC_MAINS_OK = 0;

  function __construct($value = null)
    {
    $this->value = $value;
    }

  public static function construct($alarm1, $alarm2, $tamper, $battery, $supervision_reports, $restore_reports, $trouble, $ac)
    {
    $status = new self;

    $value = 0x0000;

    if($alarm1)
      $value |= self::ALARM_1_BITMASK;
    if($alarm2)
      $value |= self::ALARM_2_BITMASK;
    if($tamper)
      $value |= self::TAMPER_BITMASK;
    if($battery)
      $value |= self::BATTERY_BITMASK;
    if($supervision_reports)
      $value |= self::SUPERVISION_REPORTS_BITMASK;
    if($restore_reports)
      $value |= self::RESTORE_REPORTS_BITMASK;
    if($trouble)
      $value |= self::TROUBLE_BITMASK;
    if($ac)
      $value |= self::AC_MAINS_BITMASK;

    $status->setValue($value);

    return $status;
    }

  /**
   * @param int $value
   */
  public function setValue($value)
    {
    $this->value = $value;
    }

  /**
   * @return int
   */
  public function getValue()
    {
    return $this->value;
    }

  public function getAlarm1()
    {
    return $this->value & self::ALARM_1_BITMASK ? self::ALARM_1_OPENED_OR_ALARMED : self::ALARM_1_CLOSED_OR_NOT_ALARMED;
    }

  public function setAlarm1($value)
    {
    if($value == self::ALARM_1_OPENED_OR_ALARMED)
      $this->value |= self::ALARM_1_BITMASK;
    else
      $this->value ^= self::ALARM_1_BITMASK;
    }

  public function getAlarm2()
    {
    return $this->value & self::ALARM_2_BITMASK ? self::ALARM_2_OPENED_OR_ALARMED : self::ALARM_2_CLOSED_OR_NOT_ALARMED;
    }

  public function setAlarm2($value)
    {
    if($value == self::ALARM_2_OPENED_OR_ALARMED)
      $this->value |= self::ALARM_2_BITMASK;
    else
      $this->value ^= self::ALARM_2_BITMASK;
    }

  public function getTamper()
    {
    return $this->value & self::TAMPER_BITMASK ? self::TAMPER_TAMPERED : self::TAMPER_NOT_TAMPERED;
    }

  public function setTamper($value)
    {
    if($value == self::TAMPER_TAMPERED)
      $this->value |= self::TAMPER_BITMASK;
    else
      $this->value ^= self::TAMPER_BITMASK;
    }

  public function getBattery()
    {
    return $this->value & self::BATTERY_BITMASK ? self::BATTERY_LOW_BATTERY : self::BATTERY_OK;
    }

  public function setBattery($value)
    {
    if($value == self::BATTERY_LOW_BATTERY)
      $this->value |= self::BATTERY_BITMASK;
    else
      $this->value ^= self::BATTERY_BITMASK;
    }

  public function getSupervisionReports()
    {
    return $this->value & self::SUPERVISION_REPORTS_BITMASK ? self::SUPERVISION_REPORTS_ON : self::SUPERVISION_REPORTS_DOES_NOT_REPORT;
    }

  public function setSupervisionReports($value)
    {
    if($value == self::SUPERVISION_REPORTS_ON)
      $this->value |= self::SUPERVISION_REPORTS_BITMASK;
    else
      $this->value ^= self::SUPERVISION_REPORTS_BITMASK;
    }

  public function getRestoreReports()
    {
    return $this->value & self::RESTORE_REPORTS_BITMASK ? self::RESTORE_REPORTS_ON : self::RESTORE_REPORTS_DOES_NOT_REPORT_RESTORE;
    }

  public function setRestoreReports($value)
    {
    if($value == self::RESTORE_REPORTS_ON)
      $this->value |= self::RESTORE_REPORTS_BITMASK;
    else
      $this->value ^= self::RESTORE_REPORTS_BITMASK;
    }

  public function getTrouble()
    {
    return $this->value & self::TROUBLE_BITMASK ? self::TROUBLE_FAILURE : self::TROUBLE_OK;
    }

  public function setTrouble($value)
    {
    if($value == self::TROUBLE_FAILURE)
      $this->value |= self::TROUBLE_BITMASK;
    else
      $this->value ^= self::TROUBLE_BITMASK;
    }

  public function getACMains()
    {
    return $this->value & self::AC_MAINS_BITMASK ? self::AC_MAINS_FAULT : self::AC_MAINS_OK;
    }

  public function setACMains($value)
    {
    if($value == self::AC_MAINS_FAULT)
      $this->value |= self::AC_MAINS_BITMASK;
    else
      $this->value ^= self::AC_MAINS_BITMASK;
    }
  }