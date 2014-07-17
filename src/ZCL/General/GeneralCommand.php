<?php

namespace Munisense\Zigbee\ZCL\General;

class GeneralCommand
  {
  const READ_ATTRIBUTES = 0x00;
  const READ_ATTRIBUTES_RESPONSE = 0x01;
  const WRITE_ATTRIBUTES = 0x02;
  const WRITE_ATTRIBUTES_UNDIVIDED = 0x03;
  const WRITE_ATTRIBUTES_RESPONSE = 0x04;
  const WRITE_ATTRIBUTES_NO_RESPONSE = 0x05;
  const CONFIGURE_REPORTING = 0x06;
  const CONFIGURE_REPORTING_RESPONSE = 0x07;
  const READ_REPORTING_CONFIGURATION = 0x08;
  const READ_REPORTING_CONFIGURATION_RESPONSE = 0x09;
  const REPORT_ATTRIBUTES = 0x0a;
  const DEFAULT_RESPONSE = 0x0b;
  const DISCOVER_ATTRIBUTES = 0x0c;
  const DISCOVER_ATTRIBUTES_RESPONSE = 0x0d;
  const READ_ATTRIBUTES_STRUCTURED = 0x0e;
  const WRITE_ATTRIBUTES_STRUCTURED = 0x0f;
  const WRITE_ATTRIBUTES_STRUCTURED_RESPONSE = 0x10;

  public static $command = array(
      self::READ_ATTRIBUTES => array("class" => "Munisense\\Zigbee\\ZCL\\General\\ReadAttributesCommand", "name" => "Read Attributes"),
      self::READ_ATTRIBUTES_RESPONSE => array("class" => "Munisense\\Zigbee\\ZCL\\General\\ReadAttributesResponseCommand", "name" => "Read Attributes Response"),
      self::WRITE_ATTRIBUTES => array("class" => "Munisense\\Zigbee\\ZCL\\General\\WriteAttributesCommand", "name" => "Write Attributes"),
      self::WRITE_ATTRIBUTES_UNDIVIDED => array("class" => "Munisense\\Zigbee\\ZCL\\General\\WriteAttributesCommand","name" =>  "Write Attributes Undivided"),
      self::WRITE_ATTRIBUTES_RESPONSE => array("class" => "Munisense\\Zigbee\\ZCL\\General\\WriteAttributesResponseCommand", "name" => "Write Attributes Response"),
      self::WRITE_ATTRIBUTES_NO_RESPONSE => array("class" => "Munisense\\Zigbee\\ZCL\\General\\WriteAttributesCommand","name" =>  "Write Attributes No Response"),
      self::CONFIGURE_REPORTING => array("class" => "Munisense\\Zigbee\\ZCL\\General\\ConfigureReportingCommand", "name" => "Configure Reporting"),
      self::CONFIGURE_REPORTING_RESPONSE => array("class" => "Munisense\\Zigbee\\ZCL\\General\\ConfigureReportingResponseCommand","name" =>  "Configure Reporting Response"),
      self::READ_REPORTING_CONFIGURATION => array("class" => "Munisense\\Zigbee\\ZCL\\General\\ReadReportingConfigurationCommand","name" =>  "Read Reporting Configuration"),
      self::READ_REPORTING_CONFIGURATION_RESPONSE => array("class" => "Munisense\\Zigbee\\ZCL\\General\\ReadReportingConfigurationResponseCommand", "name" => "Read Reporting Configuration Response"),
      self::REPORT_ATTRIBUTES => array("class" => "Munisense\\Zigbee\\ZCL\\General\\ReportAttributesCommand", "name" => "Report Attributes"),
      self::DEFAULT_RESPONSE => array("class" => "Munisense\\Zigbee\\ZCL\\General\\DefaultResponseCommand","name" =>  "Default Response"),
      self::DISCOVER_ATTRIBUTES => array("class" => "Munisense\\Zigbee\\ZCL\\General\\DiscoverAttributesCommand", "name" => "Discover Attributes"),
      self::DISCOVER_ATTRIBUTES_RESPONSE => array("class" => "Munisense\\Zigbee\\ZCL\\General\\DiscoverAttributesResponseCommand", "name" => "Discover Attributes Response"),
      self::READ_ATTRIBUTES_STRUCTURED => array("class" => "Munisense\\Zigbee\\ZCL\\General\\ReadAttributesStructuredCommand", "name" => "Read Attributes Structured"),
      self::WRITE_ATTRIBUTES_STRUCTURED => array("class" => "Munisense\\Zigbee\\ZCL\\General\\WriteAttributesCommand","name" =>  "Write Attributes Structured"),
      self::WRITE_ATTRIBUTES_STRUCTURED_RESPONSE => array("class" => "Munisense\\Zigbee\\ZCL\\General\\ZCLWriteAttributesStructuredResponseFrame", "name" => "Write Attributes Structured Response")
  );

  public static function displayCommand($command_id)
    {
    if(isset(self::$command[$command_id]))
      return self::$command[$command_id]['name'];
    else
      return "Unknown (".sprintf("0x%02x", $command_id).")";
    }
  }