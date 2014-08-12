<?php

namespace Munisense\Zigbee\ZDP;

class Command
  {
  const COMMAND_NWK_ADDR_REQ = 0x0000;
  const COMMAND_IEEE_ADDR_REQ = 0x0001;
  const COMMAND_NODE_DESC_REQ = 0x0002;
  const COMMAND_POWER_DESC_REQ = 0x0003;
  const COMMAND_SIMPLE_DESC_REQ = 0x0004;
  const COMMAND_ACTIVE_EP_REQ = 0x0005;
  const COMMAND_MATCH_DESC_REQ = 0x0006;

  const COMMAND_COMPLEX_DESC_REQ = 0x0010;
  const COMMAND_USER_DESC_REQ = 0x0011;
  const COMMAND_DISCOVERY_CACHE_REQ = 0x0012;
  const COMMAND_DEVICE_ANNCE = 0x0013;
  const COMMAND_USER_DESC_SET = 0x0014;
  const COMMAND_SYSTEM_SERVER_DISCOVERY_REQ = 0x0015;
  const COMMAND_DISCOVERY_STORE_REQ = 0x0016;
  const COMMAND_NODE_DESC_STORE_REQ = 0x0017;
  const COMMAND_POWER_DESC_STORE_REQ = 0x0018;
  const COMMAND_ACTIVE_EP_STORE_REQ = 0x0019;
  const COMMAND_SIMPLE_DESC_STORE_REQ = 0x001a;
  const COMMAND_REMOVE_NODE_CACHE_REQ = 0x001b;
  const COMMAND_FIND_NODE_CACHE_REQ = 0x001c;
  const COMMAND_EXTENDED_SIMPLE_DESC_REQ = 0x001d;
  const COMMAND_EXTENDED_ACTIVE_EP_REQ = 0x001e;

  const COMMAND_END_DEVICE_BIND_REQ = 0x0020;
  const COMMAND_BIND_REQ = 0x0021;
  const COMMAND_UNBIND_REQ = 0x0022;
  const COMMAND_BIND_REGISTER_REQ = 0x0023;
  const COMMAND_REPLACE_DEVICE_REQ = 0x0024;
  const COMMAND_STORE_BKUP_BIND_ENTRY_REQ = 0x0025;
  const COMMAND_REMOVE_BKUP_BIND_ENTRY_REQ = 0x0026;
  const COMMAND_BACKUP_BIND_TABLE_REQ = 0x0027;
  const COMMAND_RECOVER_BIND_TABLE_REQ = 0x0028;
  const COMMAND_BACKUP_SOURCE_BIND_REQ = 0x0029;
  const COMMAND_RECOVER_SOURCE_BIND_REQ = 0x002a;

  const COMMAND_MGMT_NWK_DISC_REQ = 0x0030;
  const COMMAND_MGMT_LQI_REQ = 0x0031;
  const COMMAND_MGMT_RTG_REQ = 0x0032;
  const COMMAND_MGMT_BIND_REQ = 0x0033;
  const COMMAND_MGMT_LEAVE_REQ = 0x0034;
  const COMMAND_MGMT_DIRECT_JOIN_REQ = 0x0035;
  const COMMAND_MGMT_PERMIT_JOINING_REQ = 0x0036;
  const COMMAND_MGMT_CACHE_REQ = 0x0037;
  const COMMAND_MGMT_NWK_UPDATE_REQ = 0x0038;

  const COMMAND_NWK_ADDR_RSP = 0x8000;
  const COMMAND_IEEE_ADDR_RSP = 0x8001;
  const COMMAND_NODE_DESC_RSP = 0x8002;
  const COMMAND_POWER_DESC_RSP = 0x8003;
  const COMMAND_SIMPLE_DESC_RSP = 0x8004;
  const COMMAND_ACTIVE_EP_RSP = 0x8005;
  const COMMAND_MATCH_DESC_RSP = 0x8006;

  const COMMAND_COMPLEX_DESC_RSP = 0x8010;
  const COMMAND_USER_DESC_RSP = 0x8011;
  const COMMAND_DISCOVERY_CACHE_RSP = 0x8012;
  const COMMAND_USER_DESC_CONF = 0x8014;
  const COMMAND_SYSTEM_SERVER_DISCOVERY_RSP = 0x8015;
  const COMMAND_DISCOVERY_STORE_RSP = 0x8016;
  const COMMAND_NODE_DESC_STORE_RSP = 0x8017;
  const COMMAND_POWER_DESC_STORE_RSP = 0x8018;
  const COMMAND_ACTIVE_EP_STORE_RSP = 0x8019;
  const COMMAND_SIMPLE_DESC_STORE_RSP = 0x801a;
  const COMMAND_REMOVE_NODE_CACHE_RSP = 0x801b;
  const COMMAND_FIND_NODE_CACHE_RSP = 0x801c;
  const COMMAND_EXTENDED_SIMPLE_DESC_RSP = 0x801d;
  const COMMAND_EXTENDED_ACTIVE_EP_RSP = 0x801e;

  const COMMAND_END_DEVICE_BIND_RSP = 0x8020;
  const COMMAND_BIND_RSP = 0x8021;
  const COMMAND_UNBIND_RSP = 0x8022;
  const COMMAND_BIND_REGISTER_RSP = 0x8023;
  const COMMAND_REPLACE_DEVICE_RSP = 0x8024;
  const COMMAND_STORE_BKUP_BIND_ENTRY_RSP = 0x8025;
  const COMMAND_REMOVE_BKUP_BIND_ENTRY_RSP = 0x8026;
  const COMMAND_BACKUP_BIND_TABLE_RSP = 0x8027;
  const COMMAND_RECOVER_BIND_TABLE_RSP = 0x8028;
  const COMMAND_BACKUP_SOURCE_BIND_RSP = 0x8029;
  const COMMAND_RECOVER_SOURCE_BIND_RSP = 0x802a;

  const COMMAND_MGMT_NWK_DISC_RSP = 0x8030;
  const COMMAND_MGMT_LQI_RSP = 0x8031;
  const COMMAND_MGMT_RTG_RSP = 0x8032;
  const COMMAND_MGMT_BIND_RSP = 0x8033;
  const COMMAND_MGMT_LEAVE_RSP = 0x8034;
  const COMMAND_MGMT_DIRECT_JOIN_RSP = 0x8035;
  const COMMAND_MGMT_PERMIT_JOINING_RSP = 0x8036;
  const COMMAND_MGMT_CACHE_RSP = 0x8037;
  const COMMAND_MGMT_NWK_UPDATE_RSP = 0x8038;

  public static $command = array(
    self::COMMAND_NWK_ADDR_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\NwkAddrReqCommand", "name" => "Network Address Request"),
    self::COMMAND_IEEE_ADDR_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\IEEEAddrReqCommand", "name" => "Ieee Address Request"),
    self::COMMAND_NODE_DESC_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\NodeDescReqCommand", "name" => "Node Descriptor Request"),
    self::COMMAND_POWER_DESC_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\PowerDescReqCommand", "name" => "Power Descriptor Request"),
    self::COMMAND_SIMPLE_DESC_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\SimpleDescReqCommand", "name" => "Simple Descriptor Request"),
    self::COMMAND_ACTIVE_EP_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\ActiveEPReqCommand", "name" => "Active Endpoint Request"),
    self::COMMAND_MATCH_DESC_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\MatchDescReqCommand", "name" => "Match Descriptor Request"),

    self::COMMAND_COMPLEX_DESC_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\ComplexDescReqCommand", "name" => "Complex Descriptor Request"),
    self::COMMAND_USER_DESC_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\UserDescReqCommand", "name" => "User Descriptor Request"),
    self::COMMAND_DISCOVERY_CACHE_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\DiscoveryCacheReqCommand", "name" => "Discovery Cache Request"),
    self::COMMAND_DEVICE_ANNCE => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\DeviceAnnceCommand", "Device Announce"),
    self::COMMAND_USER_DESC_SET => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\UserDescSetCommand", "User Descriptor Set Request"),

    self::COMMAND_SYSTEM_SERVER_DISCOVERY_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\SystemServerDiscoveryReqCommand", "name" => " System Server Discovery Request"),
    self::COMMAND_DISCOVERY_STORE_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\DiscoveryStoreReqCommand", "name" => "Discovery Store Request"),
    self::COMMAND_NODE_DESC_STORE_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\NodeDescStoreReqCommand", "name" => "Node Descriptor Store Request"),
    self::COMMAND_POWER_DESC_STORE_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\PowerDescStoreReqCommand", "name" => "Power Descriptor Store Request"),
    self::COMMAND_ACTIVE_EP_STORE_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\ActiveEPStoreReqCommand", "name" => "Active Endpoint Store Request"),
    self::COMMAND_SIMPLE_DESC_STORE_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\SimpleDescStoreReqCommand", "name" => "Simple Descriptor Store Request"),
    self::COMMAND_REMOVE_NODE_CACHE_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\RemoveNodeCacheReqCommand", "name" => "Remove Node Cache Request"),
    self::COMMAND_FIND_NODE_CACHE_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\FindNodeCacheReqCommand", "name" => "Find Node Cache Request"),
    self::COMMAND_EXTENDED_SIMPLE_DESC_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\ExtendedSimpleDescReqCommand", "name" => "Extended Simple Descriptor Request"),
    self::COMMAND_EXTENDED_ACTIVE_EP_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\ExtendedActiveEPReqCommand", "name" => "Extended Active Endpoint Request"),

    self::COMMAND_END_DEVICE_BIND_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\EndDeviceBindReqCommand", "name" => "End Device Bind Request"),
    self::COMMAND_BIND_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\BindReqCommand", "name" => "Bind Request"),
    self::COMMAND_UNBIND_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\UnbindReqCommand", "name" => "Unbind Request"),
    self::COMMAND_BIND_REGISTER_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\BindRegisterReqCommand", "name" => "Bind Register Request"),
    self::COMMAND_REPLACE_DEVICE_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\ReplaceDeviceReqCommand", "name" => "Replace Device Request"),
    self::COMMAND_STORE_BKUP_BIND_ENTRY_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\StoreBkupBindEntryReqCommand", "name" => "Store Backup Bind Entry Request"),
    self::COMMAND_REMOVE_BKUP_BIND_ENTRY_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\RemoveBkupBindEntryReqCommand", "name" => "Remove Backup Bind Entry Request"),
    self::COMMAND_BACKUP_BIND_TABLE_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\BackupBindTableReqCommand", "name" => "Backup Bind Table Request"),
    self::COMMAND_RECOVER_BIND_TABLE_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\RecoverBindTableReqCommand", "name" => "Recover Bind Table Request"),
    self::COMMAND_BACKUP_SOURCE_BIND_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\BackupSourceBindReqCommand", "name" => "Backup Source Bind Request"),
    self::COMMAND_RECOVER_SOURCE_BIND_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\RecoverSourceBindReqCommand", "name" => "Recover Source Bind Request"),

    self::COMMAND_MGMT_NWK_DISC_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Network\\MgmtNwkDiscReqCommand", "name" => "Management Network Discover Request"),
    self::COMMAND_MGMT_LQI_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Network\\MgmtLqiReqCommand", "name" => "Management LQI Table Request"),
    self::COMMAND_MGMT_RTG_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Network\\MgmtRtgReqCommand", "name" => "Management Routing Table Request"),
    self::COMMAND_MGMT_BIND_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Network\\MgmtBindReqCommand", "name" => "Management Bind Request"),
    self::COMMAND_MGMT_LEAVE_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Network\\MgmtLeaveReqCommand", "name" => "Management Leave Request"),
    self::COMMAND_MGMT_DIRECT_JOIN_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Network\\MgmtDirectJoinReqCommand", "name" => "Management Direct Join Request"),
    self::COMMAND_MGMT_PERMIT_JOINING_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Network\\MgmtPermitJoiningReqCommand", "name" => "Management Permit Joining Request"),
    self::COMMAND_MGMT_CACHE_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Network\\MgmtCacheReqCommand", "name" => "Management Cache Request"),
    self::COMMAND_MGMT_NWK_UPDATE_REQ => array("class" => "Munisense\\Zigbee\\ZDP\\Network\\MgmtNwkUpdateReqCommand", "name" => "Management Network Update Request"),

    self::COMMAND_NWK_ADDR_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\NwkAddrRspCommand", "name" => "Network Address Response"),
    self::COMMAND_IEEE_ADDR_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\IEEEAddrRspCommand", "name" => "Ieee Address Response"),
    self::COMMAND_NODE_DESC_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\NodeDescRspCommand", "name" => "Node Descriptor Response"),
    self::COMMAND_POWER_DESC_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\PowerDescRspCommand", "name" => "Power Descriptor Response"),
    self::COMMAND_SIMPLE_DESC_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\SimpleDescRspCommand", "name" => "Simple Descriptor Response"),
    self::COMMAND_ACTIVE_EP_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\ActiveEPRspCommand", "name" => "Active Endpoint Response"),
    self::COMMAND_MATCH_DESC_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\MatchDescRspCommand", "name" => "Match Descriptor Response"),

    self::COMMAND_COMPLEX_DESC_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\ComplexDescRspCommand", "name" => "Complex Descriptor Response"),
    self::COMMAND_USER_DESC_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\UserDescRspCommand", "name" => "User Descriptor Response"),
    self::COMMAND_DISCOVERY_CACHE_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\DiscoveryCacheRspCommand", "name" => "Discovery Cache Response"),
    self::COMMAND_USER_DESC_CONF => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\UserDescConfCommand", "User Descriptor Set Conf"),

    self::COMMAND_SYSTEM_SERVER_DISCOVERY_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\SystemServerDiscoveryRspCommand", "name" => " System Server Discovery Response"),
    self::COMMAND_DISCOVERY_STORE_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\DiscoveryStoreRspCommand", "name" => "Discovery Store Response"),
    self::COMMAND_NODE_DESC_STORE_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\NodeDescStoreRspCommand", "name" => "Node Descriptor Store Response"),
    self::COMMAND_POWER_DESC_STORE_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\PowerDescStoreRspCommand", "name" => "Power Descriptor Store Response"),
    self::COMMAND_ACTIVE_EP_STORE_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\ActiveEPStoreRspCommand", "name" => "Active Endpoint Store Response"),
    self::COMMAND_SIMPLE_DESC_STORE_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\SimpleDescStoreRspCommand", "name" => "Simple Descriptor Store Response"),
    self::COMMAND_REMOVE_NODE_CACHE_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\RemoveNodeCacheRspCommand", "name" => "Remove Node Cache Response"),
    self::COMMAND_FIND_NODE_CACHE_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\FindNodeCacheRspCommand", "name" => "Find Node Cache Response"),
    self::COMMAND_EXTENDED_SIMPLE_DESC_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\ExtendedSimpleDescRspCommand", "name" => "Extended Simple Descriptor Response"),
    self::COMMAND_EXTENDED_ACTIVE_EP_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Discovery\\ExtendedActiveEPRspCommand", "name" => "Extended Active Endpoint Response"),

    self::COMMAND_END_DEVICE_BIND_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\EndDeviceBindRspCommand", "name" => "End Device Bind Response"),
    self::COMMAND_BIND_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\BindRspCommand", "name" => "Bind Response"),
    self::COMMAND_UNBIND_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\UnbindRspCommand", "name" => "Unbind Response"),
    self::COMMAND_BIND_REGISTER_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\BindRegisterRspCommand", "name" => "Bind Register Response"),
    self::COMMAND_REPLACE_DEVICE_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\ReplaceDeviceRspCommand", "name" => "Replace Device Response"),
    self::COMMAND_STORE_BKUP_BIND_ENTRY_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\StoreBkupBindEntryRspCommand", "name" => "Store Backup Bind Entry Response"),
    self::COMMAND_REMOVE_BKUP_BIND_ENTRY_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\RemoveBkupBindEntryRspCommand", "name" => "Remove Backup Bind Entry Response"),
    self::COMMAND_BACKUP_BIND_TABLE_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\BackupBindTableRspCommand", "name" => "Backup Bind Table Response"),
    self::COMMAND_RECOVER_BIND_TABLE_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\RecoverBindTableRspCommand", "name" => "Recover Bind Table Response"),
    self::COMMAND_BACKUP_SOURCE_BIND_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\BackupSourceBindRspCommand", "name" => "Backup Source Bind Response"),
    self::COMMAND_RECOVER_SOURCE_BIND_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Binding\\RecoverSourceBindRspCommand", "name" => "Recover Source Bind Response"),

    self::COMMAND_MGMT_NWK_DISC_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Network\\MgmtNwkDiscRspCommand", "name" => "Management Network Discover Response"),
    self::COMMAND_MGMT_LQI_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Network\\MgmtLqiRspCommand", "name" => "Management LQI Table Response"),
    self::COMMAND_MGMT_RTG_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Network\\MgmtRtgRspCommand", "name" => "Management Routing Table Response"),
    self::COMMAND_MGMT_BIND_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Network\\MgmtBindRspCommand", "name" => "Management Bind Response"),
    self::COMMAND_MGMT_LEAVE_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Network\\MgmtLeaveRspCommand", "name" => "Management Leave Response"),
    self::COMMAND_MGMT_DIRECT_JOIN_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Network\\MgmtDirectJoinRspCommand", "name" => "Management Direct Join Response"),
    self::COMMAND_MGMT_PERMIT_JOINING_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Network\\MgmtPermitJoiningRspCommand", "name" => "Management Permit Joining Response"),
    self::COMMAND_MGMT_CACHE_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Network\\MgmtCacheRspCommand", "name" => "Management Cache Response"),
    self::COMMAND_MGMT_NWK_UPDATE_RSP => array("class" => "Munisense\\Zigbee\\ZDP\\Network\\MgmtNwkUpdateRspCommand", "name" => "Management Network Update Response")
  );


  public static function displayCommand($command_id)
    {
    if(isset(self::$command[$command_id]))
      return self::$command[$command_id]['name'];
    else
      return "Unknown (".sprintf("0x%04x", $command_id).")";
    }
  }