<?php

namespace Munisense\Zigbee\ZDP\Network;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\ZigbeeException;
use Munisense\Zigbee\ZDP\Command;
use Munisense\Zigbee\ZDP\IZDPCommandFrame;
use Munisense\Zigbee\ZDP\Status;

/**
 * The Mgmt_Lqi_rsp is generated in response to an Mgmt_Lqi_req.
 *
 * @package Munisense\Zigbee\ZDP\Network
 */
class MgmtLqiRspCommand extends AbstractFrame implements IZDPCommandFrame
  {
  private $status;
  private $neighbor_table_entries;
  private $start_index;

  /**
   * @var NeighborDescriptor[]
   */
  private $neighbor_table_list = [];

  public static function constructSuccess($neighbor_table_entries, $start_index, $neighbor_table_list)
    {
    $frame = new self;
    $frame->setStatus(Status::SUCCESS);
    $frame->setNeighborTableEntries($neighbor_table_entries);
    $frame->setStartIndex($start_index);
    $frame->setNeighborTableList($neighbor_table_list);
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
      $this->setNeighborTableEntries(Buffer::unpackInt8u($frame));
      $this->setStartIndex(Buffer::unpackInt8u($frame));

      $neighbor_table_list_count = Buffer::unpackInt8u($frame);
      for($i = 0; $i < $neighbor_table_list_count; $i++)
        {
        $neighbor_descriptor = new NeighborDescriptor();
        $neighbor_descriptor->consumeFrame($frame);
        $this->addNeighborDescriptor($neighbor_descriptor);
        }
      }
    }

  public function getFrame()
    {
    $frame = "";
    Buffer::packInt8u($frame, $this->getStatus());

    if($this->getStatus() == Status::SUCCESS)
      {
      Buffer::packInt8u($frame, $this->getNeighborTableEntries());
      Buffer::packInt8u($frame, $this->getStartIndex());
      Buffer::packInt8u($frame, $this->getNeighborTableListCount());

      foreach($this->getNeighborTableList() as $neighbor_descriptor)
        $frame .= $neighbor_descriptor->getFrame();
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
  public function getNeighborTableEntries()
    {
    return $this->neighbor_table_entries;
    }

  /**
   * @param mixed $neighbor_table_entries
   */
  public function setNeighborTableEntries($neighbor_table_entries)
    {
    $this->neighbor_table_entries = $neighbor_table_entries;
    }

  /**
   * @return NeighborDescriptor[]
   */
  public function getNeighborTableList()
    {
    return $this->neighbor_table_list;
    }

  public function getNeighborTableListCount()
    {
    return count($this->getNeighborTableList());
    }

  /**
   * @param NeighborDescriptor[] $neighbor_table_list
   */
  public function setNeighborTableList(array $neighbor_table_list)
    {
    $this->neighbor_table_list = [];
    foreach($neighbor_table_list as $neighbor_descriptor)
      $this->addNeighborDescriptor($neighbor_descriptor);
    }

  public function addNeighborDescriptor(NeighborDescriptor $neighbor_descriptor)
    {
    $this->neighbor_table_list[] = $neighbor_descriptor;
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
    return Command::COMMAND_MGMT_LQI_RSP;
    }
  }
