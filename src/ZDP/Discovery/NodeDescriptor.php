<?php

namespace Munisense\Zigbee\ZDP\Discovery;

use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\ZigbeeException;

/**
 * Class NodeDescriptor
 *
 * @package Munisense\Zigbee\ZDP\Discovery
 */
class NodeDescriptor extends AbstractFrame
  {
  /**
   * The logical type field of the node descriptoris three bits in length and specifies the
   * device type of the ZigBee node.
   *
   * @var int
   */
  private $logical_type = self::ZIGBEE_COORDINATOR;
  const ZIGBEE_COORDINATOR = 0b000;
  const ZIGBEE_ROUTER = 0b001;
  const ZIGBEE_END_DEVICE = 0b010;

  /**
   * The complex descriptor available field of the node descriptor isone bit in length
   * and specifies whether a complex descriptor is available on this device. If this field
   * is set to 1, a complex descriptor is available. If this field is set to 0, a complex
   * descriptor is not available.
   *
   * @var int
   */
  private $complex_descriptor_available = 0;

  /**
   * The user descriptor available field of the node descriptor is one bit in length and
   * specifies whether a user descriptor is available on this device. If this field is set to
   * 1, a user descriptor is available. If this field is set to 0, a user descriptor is not
   * available
   *
   * @var int
   */
  private $user_descriptor_available = 0;

  /**
   * The APS flags field of the node descriptor is three bits in length and specifies the
   * application support sub-layer capabilities of the node.
   * This field is currently not supported and shall be set to zero.
   *
   * @var int
   */
  private $aps_flags = 0b000;

  private $frequency_band = 0b00000;
  const FREQUENCY_SUPPORTED_868MHZ = 0;
  const FREQUENCY_SUPPORTED_902_928MHZ = 2;
  const FREQUENCY_SUPPORTED_2400_2483MHZ = 3;

  /**
   * The alternate PAN coordinator sub-field is one bit in length and shall be set to 1 if
   * this node is capable of becoming a PAN coordinator. Otherwise, the alternative
   * PAN coordinator sub-field shall be set to 0.
   *
   * @var int
   */
  private $mac_capability_alternate_pan_coordinator = self::NOT_PAN_COORDINATOR_CAPABLE;
  const NOT_PAN_COORDINATOR_CAPABLE = 0x00;
  const PAN_COORDINATOR_CAPABLE = 0x01;

  private $mac_capability_device_type = self::DEVICE_TYPE_FFD;
  const DEVICE_TYPE_RFD = 0x00;
  const DEVICE_TYPE_FFD = 0x01;

  private $mac_capability_power_source = self::POWER_SOURCE_MAINS;
  const POWER_SOURCE_MAINS = 0x01;
  const POWER_SOURCE_NOT_MAINS = 0x00;

  private $mac_capability_receiver_on_when_idle = self::RECEIVER_OFF_WHEN_IDLE;
  const RECEIVER_OFF_WHEN_IDLE = 0x00;
  const RECEIVER_ON_WHEN_IDLE = 0x01;

  private $mac_capability_security_capability = self::NOT_SECURE_CAPABLE;
  const NOT_SECURE_CAPABLE = 0x00;
  const SECURE_CAPABLE = 0x01;

  // TODO What is this exactly CCB #841
  private $mac_capability_allocate_address = 0;

  private $manufacturer_code = 0;
  private $maximum_buffer_size = 0;

  /**
   * The maximum transfer size field of the nodedescriptor is sixteen bits in length,
   * with a valid range of 0x0000-0x7fff. This field specifies the maximum size, in
   * octets, of the application sub-layer data unit (ASDU) that can be transferred from
   * this node in one single message transfer. This value can exceed the value of the
   * node maximum buffer size field (see sub-clause 2.3.2.3.8) through the use of
   * fragmentation.
   *
   * @var int
   */
  private $maximum_incoming_transfer_size = 0;

  /**
   * The server mask field of the node descriptor is sixteen bits in length, with bit
   * settings signifying the system server capabilities of this node. It is used to
   * facilitate discovery of particular system servers by other nodes on the system.
   *
   * @var int
   */
  private $server_mask = 0;

  /**
   * The maximum transfer size field of the nodedescriptor is sixteen bits in length,
   * with a valid range of 0x0000-0x7fff. This field specifies the maximum size, in
   * octets, of the application sub-layer data unit (ASDU) that can be transferred from
   * this node in one single message transfer. This value can exceed the value of the
   * node maximum buffer size field (see sub-clause 2.3.2.3.8) through the use of
   * fragmentation.
   *
   * @var int
   */
  private $maximum_outgoing_transfer_size = 0;
  private $extended_active_endpoint_list_available = 0;
  private $extended_simple_descriptor_list_available = 0;

  public static function construct($logical_type, $complex_descriptor_available, $user_descriptor_available, $aps_flags,
    $frequency_band, $mac_capability_alternate_pan_coordinator, $mac_capability_device_type, $mac_capability_power_source,
    $mac_capability_receiver_on_when_idle, $mac_capability_security_capability, $mac_capability_allocate_address, $manufacturer_code, $maximum_buffer_size,
    $maximum_incoming_transfer_size, $server_mask, $maximum_outgoing_transfer_size, $extended_active_endpoint_list_available, $extended_simple_descriptor_list_available)
    {
    $frame = new self;
    $frame->setApsFlags($aps_flags);
    $frame->setComplexDescriptorAvailable($complex_descriptor_available);
    $frame->setExtendedActiveEndpointListAvailable($extended_active_endpoint_list_available);
    $frame->setExtendedSimpleDescriptorListAvailable($extended_simple_descriptor_list_available);
    $frame->setFrequencyBand($frequency_band);
    $frame->setLogicalType($logical_type);
    $frame->setMacCapabilityAllocateAddress($mac_capability_allocate_address);
    $frame->setMacCapabilityAlternatePanCoordinator($mac_capability_alternate_pan_coordinator);
    $frame->setMacCapabilityDeviceType($mac_capability_device_type);
    $frame->setMacCapabilityPowerSource($mac_capability_power_source);
    $frame->setMacCapabilityReceiverOnWhenIdle($mac_capability_receiver_on_when_idle);
    $frame->setMacCapabilitySecurityCapability($mac_capability_security_capability);
    $frame->setManufacturerCode($manufacturer_code);
    $frame->setMaximumBufferSize($maximum_buffer_size);
    $frame->setMaximumIncomingTransferSize($maximum_incoming_transfer_size);
    $frame->setMaximumOutgoingTransferSize($maximum_outgoing_transfer_size);
    $frame->setServerMask($server_mask);
    $frame->setUserDescriptorAvailable($user_descriptor_available);
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

    $byte1 = $this->getLogicalType() & 7;
    $byte1 |= ($this->getComplexDescriptorAvailable() << 3) & 8;
    $byte1 |= ($this->getUserDescriptorAvailable() << 4) & 16;
    Buffer::packInt8u($frame, $byte1);

    $byte2 = $this->getApsFlags() & 7;
    $byte2 |= ($this->getFrequencyBand() << 3) & 248;
    Buffer::packInt8u($frame, $byte2);

    $byte_mac = ($this->getMacCapabilityAlternatePanCoordinator() >> 0) & 1;
    $byte_mac |= ($this->getMacCapabilityDeviceType() >> 1) & 2;
    $byte_mac |= ($this->getMacCapabilityPowerSource() >> 2) & 4;
    $byte_mac |= ($this->getMacCapabilityReceiverOnWhenIdle() >> 3) & 8;
    $byte_mac |= ($this->getMacCapabilitySecurityCapability() >> 6) & 64;
    $byte_mac |= ($this->getMacCapabilityAllocateAddress() >> 7) & 128;
    Buffer::packInt8u($frame, $byte_mac);

    Buffer::packInt16u($frame, $this->getManufacturerCode());
    Buffer::packInt8u($frame,  $this->getMaximumBufferSize());
    Buffer::packInt16u($frame, $this->getMaximumIncomingTransferSize());
    Buffer::packInt16u($frame, $this->getServerMask());
    Buffer::packInt16u($frame, $this->getMaximumOutgoingTransferSize());

    $byte_descriptor = ($this->getExtendedActiveEndpointListAvailable() >> 0) & 1;
    $byte_descriptor |= ($this->getExtendedSimpleDescriptorListAvailable() >> 1) & 2;
    Buffer::packInt8u($frame, $byte_descriptor);

    return $frame;
    }

  /**
   * @param string $frame
   */
  function setFrame($frame)
    {
    $byte1 = Buffer::unpackInt8u($frame);
    $this->setLogicalType(($byte1 & 7) >> 0);
    $this->setComplexDescriptorAvailable(($byte1 & 8) >> 3);
    $this->setUserDescriptorAvailable(($byte1 & 16) >> 4);

    $byte2 = Buffer::unpackInt8u($frame);
    $this->setApsFlags(($byte2 & 7) >> 0);
    $this->setFrequencyBand(($byte2 & 248) >> 3);

    $byte_mac = Buffer::unpackInt8u($frame);
    $this->setMacCapabilityAlternatePanCoordinator(($byte_mac & 1) >> 0);
    $this->setMacCapabilityDeviceType(($byte_mac & 2) >> 1);
    $this->setMacCapabilityPowerSource(($byte_mac & 4) >> 2);
    $this->setMacCapabilityReceiverOnWhenIdle(($byte_mac & 8) >> 3);
    $this->setMacCapabilitySecurityCapability(($byte_mac & 64) >> 6);
    $this->setMacCapabilityAllocateAddress(($byte_mac & 128) >> 7);

    $this->setManufacturerCode(Buffer::unpackInt16u($frame));
    $this->setMaximumBufferSize(Buffer::unpackInt8u($frame));
    $this->setMaximumIncomingTransferSize(Buffer::unpackInt16u($frame));
    $this->setServerMask(Buffer::unpackInt16u($frame));
    $this->setMaximumOutgoingTransferSize(Buffer::unpackInt16u($frame));

    $byte_descriptor = Buffer::unpackInt8u($frame);
    $this->setExtendedActiveEndpointListAvailable(($byte_descriptor & 1) >> 0);
    $this->setExtendedSimpleDescriptorListAvailable(($byte_descriptor & 2) >> 1);
    }

  /**
   * @return int
   */
  public function getApsFlags()
    {
    return $this->aps_flags;
    }

  /**
   * @param int $aps_flags
   */
  public function setApsFlags($aps_flags)
    {
    $this->aps_flags = $aps_flags;
    }

  /**
   * @return int
   */
  public function getComplexDescriptorAvailable()
    {
    return $this->complex_descriptor_available;
    }

  /**
   * @param int $complex_descriptor_available
   * @throws ZigbeeException
   */
  public function setComplexDescriptorAvailable($complex_descriptor_available)
    {
    if($complex_descriptor_available === 0 || $complex_descriptor_available === 1)
      $this->complex_descriptor_available = $complex_descriptor_available;
    else
      throw new ZigbeeException("Complex Descriptor Available may only be 0 or 1");
    }

  /**
   * @return int 0 When not available, 1 when available
   */
  public function getExtendedActiveEndpointListAvailable()
    {
    return $this->extended_active_endpoint_list_available;
    }

  /**
   * @param int $extended_active_endpoint_list_available 0 When not available, 1 when available
   * @throws ZigbeeException
   */
  public function setExtendedActiveEndpointListAvailable($extended_active_endpoint_list_available)
    {
    if($extended_active_endpoint_list_available === 0 || $extended_active_endpoint_list_available === 1)
      $this->extended_active_endpoint_list_available = $extended_active_endpoint_list_available;
    else
      throw new ZigbeeException("Extended Active Endpoint List Available may only be 0 or 1");
    }

  /**
   * @return int 0 When not available, 1 when available
   */
  public function getExtendedSimpleDescriptorListAvailable()
    {
    return $this->extended_simple_descriptor_list_available;
    }

  /**
   * @param int $extended_simple_descriptor_list_available 0 When not available, 1 when available
   * @throws ZigbeeException
   */
  public function setExtendedSimpleDescriptorListAvailable($extended_simple_descriptor_list_available)
    {
    if($extended_simple_descriptor_list_available === 0 || $extended_simple_descriptor_list_available === 1)
      $this->extended_simple_descriptor_list_available = $extended_simple_descriptor_list_available;
    else
      throw new ZigbeeException("Extended Simple Descriptor List Available may only be 0 or 1");
    }

  /**
   * @return int
   */
  public function getFrequencyBand()
    {
    return $this->frequency_band;
    }

  /**
   * A bitmap of frequencies. See constants that start with FREQUENCY.
   *
   * @param int $frequency_band
   */
  public function setFrequencyBand($frequency_band)
    {
    $this->frequency_band = $frequency_band;
    }

  /**
   * @return int
   */
  public function getLogicalType()
    {
    return $this->logical_type;
    }

  /**
   * @param int $logical_type
   *
   * @throws ZigbeeException
   */
  public function setLogicalType($logical_type)
    {
    if(in_array($logical_type, [self::ZIGBEE_COORDINATOR, self::ZIGBEE_END_DEVICE, self::ZIGBEE_ROUTER]))
      $this->logical_type = $logical_type;
    else
      throw new ZigbeeException("Invalid Logical Type supplied: ".$logical_type);
    }

  /**
   * @return int
   */
  public function getMacCapabilityAllocateAddress()
    {
    return $this->mac_capability_allocate_address;
    }

  /**
   * @param int $mac_capability_allocate_address
   */
  public function setMacCapabilityAllocateAddress($mac_capability_allocate_address)
    {
    $this->mac_capability_allocate_address = $mac_capability_allocate_address;
    }

  /**
   * @return int
   */
  public function getMacCapabilityAlternatePanCoordinator()
    {
    return $this->mac_capability_alternate_pan_coordinator;
    }

  /**
   * @param int $mac_capability_alternate_pan_coordinator
   */
  public function setMacCapabilityAlternatePanCoordinator($mac_capability_alternate_pan_coordinator)
    {
    $this->mac_capability_alternate_pan_coordinator = $mac_capability_alternate_pan_coordinator;
    }

  /**
   * @return int
   */
  public function getMacCapabilityDeviceType()
    {
    return $this->mac_capability_device_type;
    }

  /**
   * @param int $mac_capability_device_type
   */
  public function setMacCapabilityDeviceType($mac_capability_device_type)
    {
    $this->mac_capability_device_type = $mac_capability_device_type;
    }

  /**
   * @return int
   */
  public function getMacCapabilityPowerSource()
    {
    return $this->mac_capability_power_source;
    }

  /**
   * @param int $mac_capability_power_source
   */
  public function setMacCapabilityPowerSource($mac_capability_power_source)
    {
    $this->mac_capability_power_source = $mac_capability_power_source;
    }

  /**
   * @return int
   */
  public function getMacCapabilityReceiverOnWhenIdle()
    {
    return $this->mac_capability_receiver_on_when_idle;
    }

  /**
   * @param int $mac_capability_receiver_on_when_idle
   */
  public function setMacCapabilityReceiverOnWhenIdle($mac_capability_receiver_on_when_idle)
    {
    $this->mac_capability_receiver_on_when_idle = $mac_capability_receiver_on_when_idle;
    }

  /**
   * @return int
   */
  public function getMacCapabilitySecurityCapability()
    {
    return $this->mac_capability_security_capability;
    }

  /**
   * @param int $mac_capability_security_capability
   */
  public function setMacCapabilitySecurityCapability($mac_capability_security_capability)
    {
    $this->mac_capability_security_capability = $mac_capability_security_capability;
    }

  /**
   * @return mixed
   */
  public function getManufacturerCode()
    {
    return $this->manufacturer_code;
    }

  /**
   * @param int $manufacturer_code
   */
  public function setManufacturerCode($manufacturer_code)
    {
    $this->manufacturer_code = $manufacturer_code;
    }

  /**
   * @return int
   */
  public function getMaximumBufferSize()
    {
    return $this->maximum_buffer_size;
    }

  /**
   * @param int $maximum_buffer_size
   * @throws \Munisense\Zigbee\Exception\ZigbeeException
   */
  public function setMaximumBufferSize($maximum_buffer_size)
    {
    if($maximum_buffer_size >= 0x00 && $maximum_buffer_size <= 0x7f)
      $this->$maximum_buffer_size = $maximum_buffer_size;
    else
      throw new ZigbeeException("Maximum Buffer Size not within range 0x00 - 0x7f: ".sprintf("0x%02x", $maximum_buffer_size));
    }

  /**
   * @return int
   */
  public function getMaximumIncomingTransferSize()
    {
    return $this->maximum_incoming_transfer_size;
    }

  /**
   * @param int $maximum_incoming_transfer_size
   *
   * @throws \Munisense\Zigbee\Exception\ZigbeeException
   */
  public function setMaximumIncomingTransferSize($maximum_incoming_transfer_size)
    {
    if($maximum_incoming_transfer_size >= 0x0000 && $maximum_incoming_transfer_size <= 0x7fff)
      $this->maximum_incoming_transfer_size = $maximum_incoming_transfer_size;
    else
      throw new ZigbeeException("Maximum Incoming Transfer Size not within range 0x0000 - 0x7fff: ".sprintf("0x%04x", $maximum_incoming_transfer_size));
    }

  /**
   * @return int
   */
  public function getMaximumOutgoingTransferSize()
    {
    return $this->maximum_outgoing_transfer_size;
    }

  /**
   * @param $maximum_outgoing_transfer_size int
   *
   * @throws \Munisense\Zigbee\Exception\ZigbeeException
   */
  public function setMaximumOutgoingTransferSize($maximum_outgoing_transfer_size)
    {
    if($maximum_outgoing_transfer_size >= 0x0000 && $maximum_outgoing_transfer_size <= 0x7fff)
      $this->maximum_outgoing_transfer_size = $maximum_outgoing_transfer_size;
    else
      throw new ZigbeeException("Maximum Outgoing Transfer Size not within range 0x0000 - 0x7fff: ".sprintf("0x%04x", $maximum_outgoing_transfer_size));
    }

  /**
   * @return int
   */
  public function getServerMask()
    {
    return $this->server_mask;
    }

  /**
   * @param int $server_mask
   */
  public function setServerMask($server_mask)
    {
    $this->server_mask = $server_mask;
    }

  /**
   * @return int
   */
  public function getUserDescriptorAvailable()
    {
    return $this->user_descriptor_available;
    }

  /**
   * @param int $user_descriptor_available
   * @throws ZigbeeException
   */
  public function setUserDescriptorAvailable($user_descriptor_available)
    {
    if($user_descriptor_available === 0 || $user_descriptor_available === 1)
      $this->user_descriptor_available = $user_descriptor_available;
    else
      throw new ZigbeeException("User Descriptor Available may only be 0 or 1");
    }

  public function __toString()
    {
    $output = __CLASS__." (length: ".strlen($this->getFrame()).")".PHP_EOL;

    // TODO Implement the rest of the toString

    return $output;
    }
  }