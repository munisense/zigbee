<?php

namespace Munisense\Zigbee\ZDP\Network;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\ZigbeeException;
use Munisense\Zigbee\ZDP\Command;
use Munisense\Zigbee\ZDP\IZDPCommandFrame;
use Munisense\Zigbee\ZDP\Status;

/**
 * The Mgmt_Rtg_rsp is generated in response to an Mgmt_Rtg_req.
 *
 * @package Munisense\Zigbee\ZDP\Network
 */
class MgmtRtgRspCommand extends AbstractFrame implements IZDPCommandFrame
  {
  private $status;
  private $routing_table_entries;
  private $start_index;

  /**
   * @var RoutingDescriptor[]
   */
  private $routing_table_list = [];

  public static function constructSuccess($routing_table_entries, $start_index, $routing_table_list)
    {
    $frame = new self;
    $frame->setStatus(Status::SUCCESS);
    $frame->setRoutingTableEntries($routing_table_entries);
    $frame->setStartIndex($start_index);
    $frame->setRoutingTableList($routing_table_list);
    return $frame;
    }

  public static function constructFailure($status)
    {
    $frame = new self;
    $frame->setStatus($status);
    return $frame;
    }

  public function setFrame($frame)
    {
    $this->setStatus(Buffer::unpackInt8u($frame));

    if($this->getStatus() == Status::SUCCESS)
      {
      $this->setRoutingTableEntries(Buffer::unpackInt8u($frame));
      $this->setStartIndex(Buffer::unpackInt8u($frame));

      $routing_table_list_count = Buffer::unpackInt8u($frame);
      for($i = 0; $i < $routing_table_list_count; $i++)
        {
        $routing_descriptor = new RoutingDescriptor();
        $routing_descriptor->consumeFrame($frame);
        $this->addRoutingDescriptor($routing_descriptor);
        }
      }
    }

  public function getFrame()
    {
    $frame = "";
    Buffer::packInt8u($frame, $this->getStatus());

    if($this->getStatus() == Status::SUCCESS)
      {
      Buffer::packInt8u($frame, $this->getRoutingTableEntries());
      Buffer::packInt8u($frame, $this->getStartIndex());
      Buffer::packInt8u($frame, $this->getRoutingTableListCount());

      foreach($this->getRoutingTableList() as $routing_descriptor)
        $frame .= $routing_descriptor->getFrame();
      }

    return $frame;
    }

  public function setStartIndex($start_index)
    {
    $start_index = intval($start_index);
    if($start_index < 0x00 || $start_index > 0xff)
      throw new ZigbeeException("Invalid start index");

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

  /**
   * @return mixed
   */
  public function getRoutingTableEntries()
    {
    return $this->routing_table_entries;
    }

  /**
   * @param mixed $routing_table_entries
   */
  public function setRoutingTableEntries($routing_table_entries)
    {
    $this->routing_table_entries = $routing_table_entries;
    }

  /**
   * @return RoutingDescriptor[]
   */
  public function getRoutingTableList()
    {
    return $this->routing_table_list;
    }

  public function getRoutingTableListCount()
    {
    return count($this->getRoutingTableList());
    }

  /**
   * @param RoutingDescriptor[] $routing_table_list
   */
  public function setRoutingTableList(array $routing_table_list)
    {
    $this->routing_table_list = [];
    foreach($routing_table_list as $routing_descriptor)
      $this->addRoutingDescriptor($routing_descriptor);
    }

  public function addRoutingDescriptor(RoutingDescriptor $routing_descriptor)
    {
    $this->routing_table_list[] = $routing_descriptor;
    }

  /**
   * @return int
   */
  public function getStatus()
    {
    return $this->status;
    }

  /**
   * @param $status
   * @throws \Munisense\Zigbee\Exception\ZigbeeException
   */
  public function setStatus($status)
    {
    if(in_array($status, array_keys(Status::$status)))
      $this->status = $status;
    else
      throw new ZigbeeException("Invalid status supplied");
    }

  public function displayStatus()
    {
    return Status::displayStatus($this->getStatus());
    }


  public function __toString()
    {
    $output = __CLASS__." (length: ".strlen($this->getFrame()).")".PHP_EOL;
    $output .= "`- StartIndex  : ".$this->displayStartIndex().PHP_EOL;
    return $output;
    }

  /**
   * Returns the Cluster ID of this frame
   *
   * @return int
   */
  public function getClusterId()
    {
    return Command::COMMAND_MGMT_RTG_RSP;
    }
  }
