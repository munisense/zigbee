<?php

namespace Munisense\Zigbee\ZCL\General;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\ZigbeeException;


class AttributeReportingConfigurationRecord extends AbstractFrame
  {
  const DIRECTION_SERVER_TO_CLIENT = 0x00;
  const DIRECTION_CLIENT_TO_SERVER = 0x01;

  private $direction = self::DIRECTION_SERVER_TO_CLIENT;

  private $attribute_id;
  private $datatype_id;
  private $minimum_reporting_interval;
  private $maximum_reporting_interval;
  private $reportable_change;
  private $timeout_period;

  public static function constructReported($attribute_id, $datatype_id, $minimum_reporting_interval, $maximum_reporting_interval, $reportable_change)
    {
    $frame = new self;
    $frame->setAttributeId($attribute_id);
    $frame->setDirection(self::DIRECTION_SERVER_TO_CLIENT);
    $frame->setDatatypeId($datatype_id);
    $frame->setMinimumReportingInterval($minimum_reporting_interval);
    $frame->setMaximumReportingInterval($maximum_reporting_interval);
    $frame->setReportableChange($reportable_change);
    return $frame;
    }

  public static function constructReceived($attribute_id, $timeout_period)
    {
    $frame = new self;
    $frame->setAttributeId($attribute_id);
    $frame->setDirection(self::DIRECTION_CLIENT_TO_SERVER);
    $frame->setTimeoutPeriod($timeout_period);
    return $frame;
    }

  public function consumeFrame(&$frame)
    {
    $this->setDirection(Buffer::unpackInt8u($frame));
    $this->setAttributeId(Buffer::unpackInt16u($frame));

    if($this->getDirection() === self::DIRECTION_SERVER_TO_CLIENT)
      {
      $this->setDatatypeId(Buffer::unpackInt8u($frame));
      $this->setMinimumReportingInterval(Buffer::unpackInt16u($frame));
      $this->setMaximumReportingInterval(Buffer::unpackInt16u($frame));
      $this->setReportableChange(Buffer::unpackDatatype($frame, $this->getDatatypeId()));
      }
    else
      {
      $this->setTimeoutPeriod(Buffer::unpackInt16u($frame));
      }
    }

  public function setFrame($frame)
    {
    $this->consumeFrame($frame);
    }

  public function getFrame()
    {
    $frame = "";

    Buffer::packInt8u($frame, $this->getDirection());
    Buffer::packInt16u($frame, $this->getAttributeId());

    if($this->getDirection() === self::DIRECTION_SERVER_TO_CLIENT)
      {
      Buffer::packInt8u($frame, $this->getDatatypeId());
      Buffer::packInt16u($frame, $this->getMinimumReportingInterval());
      Buffer::packInt16u($frame, $this->getMaximumReportingInterval());
      Buffer::packDatatype($frame, $this->getDatatypeId(), $this->getReportableChange());
      }
    else
      {
      Buffer::packInt16u($frame, $this->getTimeoutPeriod());
      }

    return $frame;
    }

  public function setDirection($direction)
    {
    $direction = intval($direction);
    if($direction < 0x00 || $direction > 0x01)
      throw new ZigbeeException("Invalid direction");

    $this->direction = $direction;
    }

  public function getDirection()
    {
    return $this->direction;
    }

  public function displayDirection()
    {
    return sprintf("0x%04x", $this->getDirection());
    }

  public function setAttributeId($attribute_id)
    {
    $attribute_id = intval($attribute_id);
    if($attribute_id < 0x00 || $attribute_id > 0xffff)
      throw new ZigbeeException("Invalid attribute id");

    $this->attribute_id = $attribute_id;
    }

  public function getAttributeId()
    {
    return $this->attribute_id;
    }

  public function displayAttributeId()
    {
    return sprintf("0x%04x", $this->getAttributeId());
    }

  public function setDatatypeId($datatype_id)
    {
    $datatype_id = intval($datatype_id);
    if($datatype_id < 0x00 || $datatype_id > 0xff)
      throw new ZigbeeException("Invalid datatype id");

    $this->datatype_id = $datatype_id;
    }

  public function getDatatypeId()
    {
    return $this->datatype_id;
    }

  public function displayDatatypeId()
    {
    return sprintf("0x%02x", $this->getDatatypeId());
    }

  public function setMinimumReportingInterval($minimum_reporting_interval)
    {
    $minimum_reporting_interval = intval($minimum_reporting_interval);
    if($minimum_reporting_interval < 0x00 || $minimum_reporting_interval > 0xffff)
      throw new ZigbeeException("Invalid minimum reporting interval");

    $this->minimum_reporting_interval = $minimum_reporting_interval;
    }

  public function getMinimumReportingInterval()
    {
    return $this->minimum_reporting_interval;
    }

  public function displayMinimumReportingInterval()
    {
    return Buffer::displayTimeOfDay($this->getMinimumReportingInterval() * 100);
    }

  public function setMaximumReportingInterval($maximum_reporting_interval)
    {
    $maximum_reporting_interval = intval($maximum_reporting_interval);
    if($maximum_reporting_interval < 0x00 || $maximum_reporting_interval > 0xffff)
      throw new ZigbeeException("Invalid maximum reporting interval");

    $this->maximum_reporting_interval = $maximum_reporting_interval;
    }

  public function getMaximumReportingInterval()
    {
    return $this->maximum_reporting_interval;
    }

  public function displayMaximumReportingInterval()
    {
    return Buffer::displayTimeOfDay($this->getMaximumReportingInterval() * 100);
    }

  public function setReportableChange($reportable_change)
    {
    // Test if the value fits into the datatype
    Buffer::packDatatype($frame, $this->getDatatypeId(), $reportable_change);

    $this->reportable_change = $reportable_change;
    }

  public function getReportableChange()
    {
    return $this->reportable_change;
    }

  public function displayReportableChange()
    {
    return Buffer::displayDatatype($this->getDatatypeId(), $this->getReportableChange());
    }

  public function setTimeoutPeriod($timeout_period)
    {
    $timeout_period = intval($timeout_period);
    if($timeout_period < 0x00 || $timeout_period > 0xffff)
      throw new ZigbeeException("Invalid timeout period");

    $this->timeout_period = $timeout_period;
    }

  public function getTimeoutPeriod()
    {
    return $this->timeout_period;
    }

  public function displayTimeoutPeriod()
    {
    return Buffer::displayTimeOfDay($this->getTimeoutPeriod() * 1000);
    }

  public function __toString()
    {
    if($this->getDirection() === self::DIRECTION_SERVER_TO_CLIENT)
      return "AttributeId: ".$this->displayAttributeId().", ".
             "DatatypeId: ".$this->displayDatatypeId().", ".
             "Minimum Reporting Interval: ".$this->displayMinimumReportingInterval().", ".
             "Maximum Reporting Interval: ".$this->displayMaximumReportingInterval().", ".
             "ReportableChange: ".$this->displayReportableChange();
    else
      return "AttributeId: ".$this->displayAttributeId().", ".
             "Timeout Period: ".$this->getTimeoutPeriod();
    }
  }

