<?php

namespace Munisense\Zigbee\ZDP\Discovery;

use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\ZigbeeException;

class SimpleDescriptor extends AbstractFrame
  {
  private $endpoint;
  private $application_profile_identifier;
  private $application_device_identifier;
  private $application_device_version;
  private $application_input_cluster_list = [];
  private $application_output_cluster_list = [];

  public static function construct($endpoint, $application_profile_identifier, $application_device_identifier, $application_device_version,
    array $application_input_cluster_list = [], array $application_output_cluster_list = [])
    {
    $frame = new self;
    $frame->setEndpoint($endpoint);
    $frame->setApplicationProfileIdentifier($application_profile_identifier);
    $frame->setApplicationDeviceIdentifier($application_device_identifier);
    $frame->setApplicationDeviceVersion($application_device_version);
    $frame->setApplicationInputClusterList($application_input_cluster_list);
    $frame->setApplicationOutputClusterList($application_output_cluster_list);
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
    Buffer::packInt8u($frame, $this->getEndpoint());
    Buffer::packInt16u($frame, $this->getApplicationProfileIdentifier());
    Buffer::packInt16u($frame, $this->getApplicationDeviceIdentifier());

    Buffer::packInt8u($frame, $this->getApplicationDeviceVersion());

    // Input Clusters
    Buffer::packInt8u($frame, $this->getApplicationInputClusterCount());
    foreach($this->getApplicationInputClusterList() as $cluster_id)
      Buffer::packInt16u($frame, $cluster_id);

    // Output Clusters
    Buffer::packInt8u($frame, $this->getApplicationOutputClusterCount());
    foreach($this->getApplicationOutputClusterList() as $cluster_id)
      Buffer::packInt16u($frame, $cluster_id);

    return $frame;
    }

  /**
   * @param string $frame
   */
  function setFrame($frame)
    {
    $this->setEndpoint(Buffer::unpackInt8u($frame));
    $this->setApplicationProfileIdentifier(Buffer::unpackInt16u($frame));
    $this->setApplicationDeviceIdentifier(Buffer::unpackInt16u($frame));

    // Device Version is in the first 4 bits
    $byte1 = Buffer::unpackInt8u($frame);
    $this->setApplicationDeviceVersion(($byte1 & 0b00001111) >> 0);

    $input_cluster_count = Buffer::unpackInt8u($frame);
    for($i = 0; $i < $input_cluster_count; $i++)
      $this->addApplicationInputCluster(Buffer::unpackInt16u($frame));

    $output_cluster_count = Buffer::unpackInt8u($frame);
    for($i = 0; $i < $output_cluster_count; $i++)
      $this->addApplicationOutputCluster(Buffer::unpackInt16u($frame));
    }

  /**
   * @return mixed
   */
  public function getApplicationDeviceIdentifier()
    {
    return $this->application_device_identifier;
    }

  /**
   * @param mixed $application_device_identifier
   */
  public function setApplicationDeviceIdentifier($application_device_identifier)
    {
    $this->application_device_identifier = $application_device_identifier;
    }

  /**
   * @return mixed
   */
  public function getApplicationDeviceVersion()
    {
    return $this->application_device_version;
    }

  /**
   * @param $application_device_version
   * @throws \Munisense\Zigbee\Exception\ZigbeeException
   */
  public function setApplicationDeviceVersion($application_device_version)
    {
    if($application_device_version >= 0b00000000 && $application_device_version <= 0b00001111)
      $this->application_device_version = $application_device_version;
    else
      throw new ZigbeeException("Application Device Version must be in the range of 0 - 10");
    }

  /**
   * @return mixed
   */
  public function getApplicationInputClusterCount()
    {
    return count($this->getApplicationInputClusterList());
    }

  /**
   * @return array
   */
  public function getApplicationInputClusterList()
    {
    return $this->application_input_cluster_list;
    }

  /**
   * @param array $application_input_cluster_list
   */
  public function setApplicationInputClusterList($application_input_cluster_list)
    {
    $this->application_input_cluster_list = $application_input_cluster_list;
    }

  public function addApplicationInputCluster($application_input_cluster)
    {
    $this->application_input_cluster_list[] = $application_input_cluster;
    }

  /**
   * @return mixed
   */
  public function getApplicationOutputClusterCount()
    {
    return count($this->getApplicationOutputClusterList());
    }

  /**
   * @return array
   */
  public function getApplicationOutputClusterList()
    {
    return $this->application_output_cluster_list;
    }

  /**
   * @param array $application_output_cluster_list
   */
  public function setApplicationOutputClusterList($application_output_cluster_list)
    {
    $this->application_output_cluster_list = $application_output_cluster_list;
    }

  public function addApplicationOutputCluster($application_output_cluster)
    {
    $this->application_output_cluster_list[] = $application_output_cluster;
    }

  /**
   * @return mixed
   */
  public function getApplicationProfileIdentifier()
    {
    return $this->application_profile_identifier;
    }

  /**
   * @param mixed $application_profile_identifier
   */
  public function setApplicationProfileIdentifier($application_profile_identifier)
    {
    $this->application_profile_identifier = $application_profile_identifier;
    }

  /**
   * @return mixed
   */
  public function getEndpoint()
    {
    return $this->endpoint;
    }

  /**
   * @param mixed $endpoint
   */
  public function setEndpoint($endpoint)
    {
    $this->endpoint = $endpoint;
    }

  public function __toString()
    {
    $output = __CLASS__." (length: ".strlen($this->getFrame()).")".PHP_EOL;

    // TODO Implement the rest of the toString

    return $output;
    }
  }