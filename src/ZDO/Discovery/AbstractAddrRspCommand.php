<?php

namespace Munisense\Zigbee\ZDO\Discovery;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\MuniZigbeeException;
use Munisense\Zigbee\ZDO\IZDOCommandFrame;
use Munisense\Zigbee\ZDO\Status;

/**
 * Base class for NwkAddrRsp and IEEEAddrRsp because they are similar in structure
 *
 * @package Munisense\Zigbee\ZDO\Discovery
 *
 * Note: This command is tricky in that it needs state from the request (request_type) to properly decide how it needs to
 * return it's frame.
 *
 * NumAssocDev is not a field on it's own, it is derived from the length of AssociatedDeviceList
 */
abstract class AbstractAddrRspCommand extends AbstractFrame implements IZDOCommandFrame
  {
  private static $allowed_statusses = [Status::SUCCESS, Status::INV_REQUESTTYPE, Status::DEVICE_NOT_FOUND];

  private $status;
  private $ieee_address_remote_dev;
  private $nwk_addr_remote_dev;
  private $start_index;
  private $associated_device_list = [];

  const REQUEST_TYPE_SINGLE = 0x00;
  const REQUEST_TYPE_EXTENDED = 0x01;
  private $request_type = self::REQUEST_TYPE_SINGLE;

  public static function constructSingle($status, $ieee_address_remote_dev, $nwk_addr_remote_dev)
    {
    $frame = new static;
    $frame->setRequestType(self::REQUEST_TYPE_SINGLE);
    $frame->setStatus($status);
    $frame->setIeeeAddressRemoteDev($ieee_address_remote_dev);
    $frame->setNwkAddrRemoteDev($nwk_addr_remote_dev);
    return $frame;
    }

  public static function constructExtended($status, $ieee_address_remote_dev, $nwk_addr_remote_dev, $start_index, array $associated_device_list)
    {
    $frame = new static;
    $frame->setRequestType(self::REQUEST_TYPE_EXTENDED);
    $frame->setStatus($status);
    $frame->setIeeeAddressRemoteDev($ieee_address_remote_dev);
    $frame->setNwkAddrRemoteDev($nwk_addr_remote_dev);
    $frame->setStartIndex($start_index);
    $frame->setAssociatedDeviceList($associated_device_list);
    return $frame;
    }

  public function setFrame($frame)
    {
    $this->setStatus(Buffer::unpackInt8u($frame));
    $this->setIeeeAddressRemoteDev(Buffer::unpackEui64($frame));
    $this->setNwkAddrRemoteDev(Buffer::unpackInt16u($frame));

    if(strlen($frame) > 0)
      {
      $this->setRequestType(self::REQUEST_TYPE_EXTENDED);
      $num_assoc_dev = Buffer::unpackInt8u($frame);

      // If there are any devices listed
      if($num_assoc_dev > 0)
        {
        $this->setStartIndex(Buffer::unpackInt8u($frame));

        for($i = 0; $i < $num_assoc_dev; $i++)
          $this->addAssociatedDevice(Buffer::unpackInt16u($frame));
        }
      }
    }

  public function getFrame()
    {
    $frame = "";

    Buffer::packInt8u($frame, $this->getStatus());
    Buffer::packEui64($frame, $this->getIeeeAddressRemoteDev());
    Buffer::packInt16u($frame, $this->getNwkAddrRemoteDev());

    // Omit the other fields if the request type is SINGLE or status is not SUCCESS
    if($this->getRequestType() == self::REQUEST_TYPE_EXTENDED && $this->getStatus() == Status::SUCCESS)
      {
      Buffer::packInt8u($frame, $this->getNumAssocDev());

      // Omit the other fields if there are no associated devices listed
      if($this->getNumAssocDev() != 0)
        {
        Buffer::packInt8u($frame, $this->getStartIndex());

        foreach($this->getAssociatedDeviceList() as $assoc_dev)
          Buffer::packInt16u($frame, $assoc_dev);
        }
      }

    return $frame;
    }

  /**
   * @return int
   */
  public function getRequestType()
    {
    return $this->request_type;
    }

  /**
   * @param int $request_type
   */
  public function setRequestType($request_type)
    {
    $this->request_type = $request_type;
    }



  /**
   * @return array
   */
  public function getAssociatedDeviceList()
    {
    return $this->associated_device_list;
    }

  /**
   * @param array $associated_device_list
   */
  public function setAssociatedDeviceList(array $associated_device_list)
    {
    $this->associated_device_list = [];

    foreach($associated_device_list as $assoc_dev)
      $this->addAssociatedDevice($assoc_dev);
    }

  public function addAssociatedDevice($assoc_dev)
    {
    if($assoc_dev >= 0x0000 && $assoc_dev <= 0xffff)
      $this->associated_device_list[] = $assoc_dev;
    else
      throw new MuniZigbeeException("Associated Device should be an identifier between 0x0000 and 0xffff");
    }

  /**
   * @return mixed
   */
  public function getIeeeAddressRemoteDev()
    {
    return $this->ieee_address_remote_dev;
    }

  /**
   * @param mixed $ieee_address_remote_dev
   */
  public function setIeeeAddressRemoteDev($ieee_address_remote_dev)
    {
    $this->ieee_address_remote_dev = $ieee_address_remote_dev;
    }

  /**
   * @return mixed
   */
  public function getNumAssocDev()
    {
    return count($this->associated_device_list);
    }

  /**
   * @return mixed
   */
  public function getNwkAddrRemoteDev()
    {
    return $this->nwk_addr_remote_dev;
    }

  /**
   * @param mixed $nwk_addr_remote_dev
   */
  public function setNwkAddrRemoteDev($nwk_addr_remote_dev)
    {
    $this->nwk_addr_remote_dev = $nwk_addr_remote_dev;
    }

  public function displayNwkAddrRemoteDev()
    {
    return Buffer::displayInt16u($this->getNwkAddrRemoteDev());
    }


  /**
   * @return mixed
   */
  public function getStatus()
    {
    return $this->status;
    }

  /**
   * @param $status
   * @throws \Munisense\Zigbee\Exception\MuniZigbeeException
   */
  public function setStatus($status)
    {
    if(in_array($status, self::$allowed_statusses))
      $this->status = $status;
    else
      throw new MuniZigbeeException("Invalid status supplied");
    }

  public function displayIeeeAddressRemoteDev()
    {
    return Buffer::displayEui64($this->getIeeeAddressRemoteDev());
    }

  public function setStartIndex($start_index)
    {
    $start_index = intval($start_index);
    if($start_index < 0x00 || $start_index > 0xff)
      throw new MuniZigbeeException("Invalid start index");

    $this->start_index = $start_index;
    }

  public function getStartIndex()
    {
    return $this->start_index;
    }

  public function displayStartIndex()
    {
    return sprintf("0x%02x", $this->getStartIndex());
    }

  public function __toString()
    {
    $output = __CLASS__." (length: ".strlen($this->getFrame()).")".PHP_EOL;
    $output .= "|- IeeeAddr    : ".$this->displayIeeeAddressRemoteDev().PHP_EOL;
    $output .= "|- NwkAddr     : ".$this->displayNwkAddrRemoteDev().PHP_EOL;

    if($this->getRequestType() == self::REQUEST_TYPE_EXTENDED && $this->getStatus() == Status::SUCCESS)
      {
      $output .= "|- NumAssocDev  : ".$this->getNumAssocDev().PHP_EOL;

      if($this->getNumAssocDev() != 0)
        {
        $output .= "`- StartIndex  : ".$this->displayStartIndex().PHP_EOL;

        foreach($this->getAssociatedDeviceList() as $assoc_dev)
          $output .= "  |- ".Buffer::displayInt16u($assoc_dev).PHP_EOL;
        }
      }

    return $output;
    }
  }

