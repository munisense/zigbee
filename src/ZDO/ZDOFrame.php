<?php

namespace Munisense\Zigbee\ZDO;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\MuniZigbeeException;
use Munisense\Zigbee\IFrame;

class ZDOFrame
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
  const COMMAND_DEVICE_ANNOUNCE = 0x0013;
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

  private $command_id = 0xffff;
  private $transaction_id = 0x00;

  private $payload = "";

  public function __construct($frame = null, $command_id = 0xffff)
    {
    $this->setCommandId($command_id);

    if($frame !== null)
      $this->setFrame($frame);
    }

  public function setFrame($frame)
    {
    $this->setTransactionId(Buffer::unpackInt8u($frame));
    $this->setPayload($frame);
    }

  public function getFrame()
    {
    $frame = "";

    Buffer::packInt8u($frame, $this->getTransactionId());
    $frame .= $this->getPayload();

    return $frame;
    }

  public function displayFrame()
    {
    return Buffer::displayOctetString($this->getFrame());
    }

  public function setTransactionId($transaction_id)
    {
    $transaction_id = intval($transaction_id);
    if($transaction_id < 0x00 || $transaction_id > 0xff)
      throw new MuniZigbeeException("Invalid transaction id: ".$transaction_id);

    $this->transaction_id = $transaction_id;
    }

  public function getTransactionId()
    {
    return $this->transaction_id;
    }

  public function displayTransactionId()
    {
    return sprintf("0x%02x", $this->getTransactionId());
    }

  public function setCommandId($command_id)
    {
    $command_id = intval($command_id);
    if($command_id < 0x0000 || $command_id > 0xffff)
      throw new MuniZigbeeException("Invalid command id");

    $this->command_id = $command_id;
    }

  public function getCommandId()
    {
    return $this->command_id;
    }

  public function displayCommandId()
    {
    $zdo_commands_arr = $this->getZDOCommands();
    $command_id = $this->getCommandId();
    $output = "unknown";
    if(isset($zdo_commands_arr[$command_id]))
      $output = $zdo_commands_arr[$command_id][1];

    return sprintf("%s (0x%02x)", $output, $this->getCommandId());
    }

  public function setPayload($payload)
    {
    $this->payload = $payload;
    }

  public function getPayload()
    {
    return $this->payload;
    }

  public function setPayloadObject(IFrame $object)
    {
    $class = get_class($object);
    foreach($this->getZDOCommands() as $command_id => $zdo_command_arr)
      if($zdo_command_arr[0] == $class)
        {
        $this->setCommandId($command_id);
        $this->setPayload($object->getFrame());
        return;
        }

    throw new MuniZigbeeException("Unknown payload object");
    }

  public function getPayloadObject()
    {
    $zdo_commands = $this->getZDOCommands();
    $command_id = $this->getCommandId();

    if(isset($zdo_commands[$command_id]) && class_exists($zdo_commands[$command_id][0]))
      return new $zdo_commands[$command_id][0]($this->getPayload());

    throw new MuniZigbeeException("Payload class not found");
    }

  public function displayPayload()
    {
    return Buffer::displayOctetString($this->getPayload());
    }

  private function getZDOCommands()
    {
    return array(self::COMMAND_NWK_ADDR_REQ => array("ZDONwkAddrReqFrame", "Network Address Request"),
                 self::COMMAND_IEEE_ADDR_REQ => array("ZigbeeZDOIeeeAddrReqFrame", "Ieee Address Request"),
                 self::COMMAND_NODE_DESC_REQ => array("ZigbeeZDONodeDescReqFrame", "Node Descriptor Request"),
                 self::COMMAND_POWER_DESC_REQ => array("ZigbeeZDOPowerDescReqFrame", "Power Descriptor Request"),
                 self::COMMAND_SIMPLE_DESC_REQ => array("ZigbeeZDOSimpleDescReqFrame", "Simple Descriptor Request"),
                 self::COMMAND_ACTIVE_EP_REQ => array("ZigbeeZDOActiveEpReqFrame", "Active Endpoint Request"),
                 self::COMMAND_MATCH_DESC_REQ => array("ZigbeeZDOMatchDescReqFrame", "Match Descriptor Request"),

                 self::COMMAND_COMPLEX_DESC_REQ => array("ZigbeeZDOComplexDescReqFrame", "Complex Descriptor Request"),
                 self::COMMAND_USER_DESC_REQ => array("ZigbeeZDOUserDescReqFrame", "User Descriptor Request"),
                 self::COMMAND_DISCOVERY_CACHE_REQ => array("ZigbeeZDODiscoveryCacheReqFrame", "Discovery Cache Request"),
                 self::COMMAND_DEVICE_ANNOUNCE => array("ZigbeeZDODeviceAnnounceFrame", "Device Announce"),
                 self::COMMAND_USER_DESC_SET => array("ZigbeeZDOUserDescrSetFrame", "User Descriptor Set Request"),

                 self::COMMAND_SYSTEM_SERVER_DISCOVERY_REQ => array("ZigbeeZDOSystemServerDiscoveryReqFrame", " System Server Discovery Request"),
                 self::COMMAND_DISCOVERY_STORE_REQ => array("ZigbeeZDODiscoveryStoreReqFrame", "Discovery Store Request"),
                 self::COMMAND_NODE_DESC_STORE_REQ => array("ZigbeeZDONodeDescStoreReqFrame", "Node Descriptor Store Request"),
                 self::COMMAND_POWER_DESC_STORE_REQ => array("ZigbeeZDOPowerDescStoreReqFrame", "Power Descriptor Store Request"),
                 self::COMMAND_ACTIVE_EP_STORE_REQ => array("ZigbeeZDOActiveEpStoreReqFrame", "Active Endpoint Store Request"),
                 self::COMMAND_SIMPLE_DESC_STORE_REQ => array("ZigbeeZDOSimpleDescStoreReqFrame", "Simple Descriptor Store Request"),
                 self::COMMAND_REMOVE_NODE_CACHE_REQ => array("ZigbeeZDORemoveNodeCacheReqFrame", "Remove Node Cache Request"),
                 self::COMMAND_FIND_NODE_CACHE_REQ => array("ZigbeeZDOFindNodeCacheReqFrame", "Find Node Cache Request"),
                 self::COMMAND_EXTENDED_SIMPLE_DESC_REQ => array("ZigbeeZDOExtendedSimpleDescReqFrame", "Extended Simple Descriptor Request"),
                 self::COMMAND_EXTENDED_ACTIVE_EP_REQ => array("ZigbeeZDOExtendedActiveEpReqFrame", "Extended Active Endpoint Request"),

                 self::COMMAND_END_DEVICE_BIND_REQ => array("ZigbeeZDOEndDeviceBindReqFrame", "End Device Bind Request"),
                 self::COMMAND_BIND_REQ => array("ZigbeeZDOBindReqFrame", "Bind Request"),
                 self::COMMAND_UNBIND_REQ => array("ZigbeeZDOUnbindReqFrame", "Unbind Request"),
                 self::COMMAND_BIND_REGISTER_REQ => array("ZigbeeZDOBindRegisterReqFrame", "Bind Register Request"),
                 self::COMMAND_REPLACE_DEVICE_REQ => array("ZigbeeZDOReplaceDeviceReqFrame", "Replace Device Request"),
                 self::COMMAND_STORE_BKUP_BIND_ENTRY_REQ => array("ZigbeeZDOStoreBkupBindEntryReqFrame", "Store Backup Bind Entry Request"),
                 self::COMMAND_REMOVE_BKUP_BIND_ENTRY_REQ => array("ZigbeeZDORemoveBkupBindEntryReqFrame", "Remove Backup Bind Entry Request"),
                 self::COMMAND_BACKUP_BIND_TABLE_REQ => array("ZigbeeZDOBackupBindTableReqFrame", "Backup Bind Table Request"),
                 self::COMMAND_RECOVER_BIND_TABLE_REQ => array("ZigbeeZDORecoverBindTableReqFrame", "Recover Bind Table Request"),
                 self::COMMAND_BACKUP_SOURCE_BIND_REQ => array("ZigbeeZDOBackupSourceBindReqFrame", "Backup Source Bind Request"),
                 self::COMMAND_RECOVER_SOURCE_BIND_REQ => array("ZigbeeZDORecoverSourceBindReqFrame", "Recover Source Bind Request"),

                 self::COMMAND_MGMT_NWK_DISC_REQ => array("ZigbeeZDONwkDiscReqFrame", "Management Network Discover Request"),
                 self::COMMAND_MGMT_LQI_REQ => array("ZigbeeZDOLqiReqFrame", "Management LQI Table Request"),
                 self::COMMAND_MGMT_RTG_REQ => array("ZigbeeZDORtgReqFrame", "Management Routing Table Request"),
                 self::COMMAND_MGMT_BIND_REQ => array("ZigbeeZDOBindReqFrame", "Management Bind Request"),
                 self::COMMAND_MGMT_LEAVE_REQ => array("ZigbeeZDOLeaveReqFrame", "Management Leave Request"),
                 self::COMMAND_MGMT_DIRECT_JOIN_REQ => array("ZigbeeZDODirectJoinReqFrame", "Management Direct Join Request"),
                 self::COMMAND_MGMT_PERMIT_JOINING_REQ => array("ZigbeeZDOPermitJoiningReqFrame", "Management Permit Joining Request"),
                 self::COMMAND_MGMT_CACHE_REQ => array("ZigbeeZDOCacheReqFrame", "Management Cache Request"),
                 self::COMMAND_MGMT_NWK_UPDATE_REQ => array("ZigbeeZDONwkUpdateReqFrame", "Management Network Update Request"),

                 self::COMMAND_NWK_ADDR_RSP => array("ZigbeeZDONwkAddrRspFrame", "Network Address Response"),
                 self::COMMAND_IEEE_ADDR_RSP => array("ZigbeeZDOIeeeAddrRspFrame", "Ieee Address Response"),
                 self::COMMAND_NODE_DESC_RSP => array("ZigbeeZDONodeDescRspFrame", "Node Descriptor Response"),
                 self::COMMAND_POWER_DESC_RSP => array("ZigbeeZDOPowerDescRspFrame", "Power Descriptor Response"),
                 self::COMMAND_SIMPLE_DESC_RSP => array("ZigbeeZDOSimpleDescRspFrame", "Simple Descriptor Response"),
                 self::COMMAND_ACTIVE_EP_RSP => array("ZigbeeZDOActiveEpRspFrame", "Active Endpoint Response"),
                 self::COMMAND_MATCH_DESC_RSP => array("ZigbeeZDOMatchDescRspFrame", "Match Descriptor Response"),

                 self::COMMAND_COMPLEX_DESC_RSP => array("ZigbeeZDOComplexDescRspFrame", "Complex Descriptor Response"),
                 self::COMMAND_USER_DESC_RSP => array("ZigbeeZDOUserDescRspFrame", "User Descriptor Response"),
                 self::COMMAND_DISCOVERY_CACHE_RSP => array("ZigbeeZDODiscoveryCacheRspFrame", "Discovery Cache Response"),
                 self::COMMAND_USER_DESC_CONF => array("ZigbeeZDOUserDescrConfFrame", "User Descriptor Set Conf"),

                 self::COMMAND_SYSTEM_SERVER_DISCOVERY_RSP => array("ZigbeeZDOSystemServerDiscoveryRspFrame", " System Server Discovery Response"),
                 self::COMMAND_DISCOVERY_STORE_RSP => array("ZigbeeZDODiscoveryStoreRspFrame", "Discovery Store Response"),
                 self::COMMAND_NODE_DESC_STORE_RSP => array("ZigbeeZDONodeDescStoreRspFrame", "Node Descriptor Store Response"),
                 self::COMMAND_POWER_DESC_STORE_RSP => array("ZigbeeZDOPowerDescStoreRspFrame", "Power Descriptor Store Response"),
                 self::COMMAND_ACTIVE_EP_STORE_RSP => array("ZigbeeZDOActiveEpStoreRspFrame", "Active Endpoint Store Response"),
                 self::COMMAND_SIMPLE_DESC_STORE_RSP => array("ZigbeeZDOSimpleDescStoreRspFrame", "Simple Descriptor Store Response"),
                 self::COMMAND_REMOVE_NODE_CACHE_RSP => array("ZigbeeZDORemoveNodeCacheRspFrame", "Remove Node Cache Response"),
                 self::COMMAND_FIND_NODE_CACHE_RSP => array("ZigbeeZDOFindNodeCacheRspFrame", "Find Node Cache Response"),
                 self::COMMAND_EXTENDED_SIMPLE_DESC_RSP => array("ZigbeeZDOExtendedSimpleDescRspFrame", "Extended Simple Descriptor Response"),
                 self::COMMAND_EXTENDED_ACTIVE_EP_RSP => array("ZigbeeZDOExtendedActiveEpRspFrame", "Extended Active Endpoint Response"),

                 self::COMMAND_END_DEVICE_BIND_RSP => array("ZigbeeZDOEndDeviceBindRspFrame", "End Device Bind Response"),
                 self::COMMAND_BIND_RSP => array("ZigbeeZDOBindRspFrame", "Bind Response"),
                 self::COMMAND_UNBIND_RSP => array("ZigbeeZDOUnbindRspFrame", "Unbind Response"),
                 self::COMMAND_BIND_REGISTER_RSP => array("ZigbeeZDOBindRegisterRspFrame", "Bind Register Response"),
                 self::COMMAND_REPLACE_DEVICE_RSP => array("ZigbeeZDOReplaceDeviceRspFrame", "Replace Device Response"),
                 self::COMMAND_STORE_BKUP_BIND_ENTRY_RSP => array("ZigbeeZDOStoreBkupBindEntryRspFrame", "Store Backup Bind Entry Response"),
                 self::COMMAND_REMOVE_BKUP_BIND_ENTRY_RSP => array("ZigbeeZDORemoveBkupBindEntryRspFrame", "Remove Backup Bind Entry Response"),
                 self::COMMAND_BACKUP_BIND_TABLE_RSP => array("ZigbeeZDOBackupBindTableRspFrame", "Backup Bind Table Response"),
                 self::COMMAND_RECOVER_BIND_TABLE_RSP => array("ZigbeeZDORecoverBindTableRspFrame", "Recover Bind Table Response"),
                 self::COMMAND_BACKUP_SOURCE_BIND_RSP => array("ZigbeeZDOBackupSourceBindRspFrame", "Backup Source Bind Response"),
                 self::COMMAND_RECOVER_SOURCE_BIND_RSP => array("ZigbeeZDORecoverSourceBindRspFrame", "Recover Source Bind Response"),

                 self::COMMAND_MGMT_NWK_DISC_RSP => array("ZigbeeZDONwkDiscRspFrame", "Management Network Discover Response"),
                 self::COMMAND_MGMT_LQI_RSP => array("ZigbeeZDOLqiRspFrame", "Management LQI Table Response"),
                 self::COMMAND_MGMT_RTG_RSP => array("ZigbeeZDORtgRspFrame", "Management Routing Table Response"),
                 self::COMMAND_MGMT_BIND_RSP => array("ZigbeeZDOBindRspFrame", "Management Bind Response"),
                 self::COMMAND_MGMT_LEAVE_RSP => array("ZigbeeZDOLeaveRspFrame", "Management Leave Response"),
                 self::COMMAND_MGMT_DIRECT_JOIN_RSP => array("ZigbeeZDODirectJoinRspFrame", "Management Direct Join Response"),
                 self::COMMAND_MGMT_PERMIT_JOINING_RSP => array("ZigbeeZDOPermitJoiningRspFrame", "Management Permit Joining Response"),
                 self::COMMAND_MGMT_CACHE_RSP => array("ZigbeeZDOCacheRspFrame", "Management Cache Response"),
                 self::COMMAND_MGMT_NWK_UPDATE_RSP => array("ZigbeeZDONwkUpdateRspFrame", "Management Network Update Response"));
    }

  public function __toString()
    {
    $output =  __CLASS__." (length: ".strlen($this->getFrame()).", command: ".$this->displayCommandId().")".PHP_EOL;
    $output .= "|- TransactionId    : ".$this->displayTransactionId().PHP_EOL;
    $output .= "|- payload (length: ".strlen($this->getPayload()).")".PHP_EOL;

    try
      {
      $output .= preg_replace("/^   /", "`- ", preg_replace("/^/m", "   ", $this->getPayloadObject()));
      }
    catch(MuniZigbeeException $e)
      {
      $output .= "`-> ".$this->displayPayload().PHP_EOL;
      }

    return $output;
    }
  }

