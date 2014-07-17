<?php

namespace Munisense\Zigbee\ZCL\IAS_Zone;


class ZoneStatusTest extends \PHPUnit_Framework_TestCase
  {
  public function testSetValueAndGetters()
    {
    $status = new ZoneStatus();
    $status->setValue(0xff);

    $this->assertEquals(ZoneStatus::ALARM_1_OPENED_OR_ALARMED, $status->getAlarm1());
    $this->assertEquals(ZoneStatus::ALARM_2_OPENED_OR_ALARMED, $status->getAlarm2());
    $this->assertEquals(ZoneStatus::TAMPER_TAMPERED, $status->getTamper());
    $this->assertEquals(ZoneStatus::BATTERY_LOW_BATTERY, $status->getBattery());
    $this->assertEquals(ZoneStatus::SUPERVISION_REPORTS_ON, $status->getSupervisionReports());
    $this->assertEquals(ZoneStatus::RESTORE_REPORTS_ON, $status->getRestoreReports());
    $this->assertEquals(ZoneStatus::TROUBLE_FAILURE, $status->getTrouble());
    $this->assertEquals(ZoneStatus::AC_MAINS_FAULT, $status->getACMains());

    $status->setValue(0x00);

    $this->assertEquals(ZoneStatus::ALARM_1_CLOSED_OR_NOT_ALARMED, $status->getAlarm1());
    $this->assertEquals(ZoneStatus::ALARM_2_CLOSED_OR_NOT_ALARMED, $status->getAlarm2());
    $this->assertEquals(ZoneStatus::TAMPER_NOT_TAMPERED, $status->getTamper());
    $this->assertEquals(ZoneStatus::BATTERY_OK, $status->getBattery());
    $this->assertEquals(ZoneStatus::SUPERVISION_REPORTS_DOES_NOT_REPORT, $status->getSupervisionReports());
    $this->assertEquals(ZoneStatus::RESTORE_REPORTS_DOES_NOT_REPORT_RESTORE, $status->getRestoreReports());
    $this->assertEquals(ZoneStatus::TROUBLE_OK, $status->getTrouble());
    $this->assertEquals(ZoneStatus::AC_MAINS_OK, $status->getACMains());
    }

  public function testStaticConstruct()
    {
    $status = ZoneStatus::construct(
        ZoneStatus::ALARM_1_OPENED_OR_ALARMED,
        ZoneStatus::ALARM_2_OPENED_OR_ALARMED,
        ZoneStatus::TAMPER_TAMPERED,
        ZoneStatus::BATTERY_LOW_BATTERY,
        ZoneStatus::SUPERVISION_REPORTS_ON,
        ZoneStatus::RESTORE_REPORTS_ON,
        ZoneStatus::TROUBLE_FAILURE,
        ZoneStatus::AC_MAINS_FAULT
    );

    $this->assertEquals(0xff, $status->getValue());
    }

  public function testSetters()
    {
    $status = new ZoneStatus();
    $this->assertEquals(0x00, $status->getValue());

    // First start setting them all
    $status->setAlarm1(ZoneStatus::ALARM_1_OPENED_OR_ALARMED);
    $this->assertEquals(0x01, $status->getValue());

    $status->setAlarm2(ZoneStatus::ALARM_2_OPENED_OR_ALARMED);
    $this->assertEquals(0x03, $status->getValue());

    $status->setTamper(ZoneStatus::TAMPER_TAMPERED);
    $this->assertEquals(0x07, $status->getValue());

    $status->setBattery(ZoneStatus::BATTERY_LOW_BATTERY);
    $this->assertEquals(0x0F, $status->getValue());

    $status->setSupervisionReports(ZoneStatus::SUPERVISION_REPORTS_ON);
    $this->assertEquals(0x1F, $status->getValue());

    $status->setRestoreReports(ZoneStatus::RESTORE_REPORTS_ON);
    $this->assertEquals(0x3F, $status->getValue());

    $status->setTrouble(ZoneStatus::TROUBLE_FAILURE);
    $this->assertEquals(0x7F, $status->getValue());

    $status->setACMains(ZoneStatus::AC_MAINS_FAULT);
    $this->assertEquals(0xFF, $status->getValue());

    // Now unset them all
    $status->setAlarm1(ZoneStatus::ALARM_1_CLOSED_OR_NOT_ALARMED);
    $this->assertEquals(0xFE, $status->getValue());

    $status->setAlarm2(ZoneStatus::ALARM_2_CLOSED_OR_NOT_ALARMED);
    $this->assertEquals(0xFC, $status->getValue());

    $status->setTamper(ZoneStatus::TAMPER_NOT_TAMPERED);
    $this->assertEquals(0xF8, $status->getValue());

    $status->setBattery(ZoneStatus::BATTERY_OK);
    $this->assertEquals(0xF0, $status->getValue());

    $status->setSupervisionReports(ZoneStatus::SUPERVISION_REPORTS_DOES_NOT_REPORT);
    $this->assertEquals(0xE0, $status->getValue());

    $status->setRestoreReports(ZoneStatus::RESTORE_REPORTS_DOES_NOT_REPORT_RESTORE);
    $this->assertEquals(0xC0, $status->getValue());

    $status->setTrouble(ZoneStatus::TROUBLE_OK);
    $this->assertEquals(0x80, $status->getValue());

    $status->setACMains(ZoneStatus::AC_MAINS_OK);
    $this->assertEquals(0x00, $status->getValue());
    }
  }
 