<?php
/**
 * Interface for clusters that define their ID, name, and any cluster specific commands they might have.
 */
namespace Munisense\Zigbee\ZCL;

use Munisense\Zigbee\Exception\MuniZigbeeException;

interface ICluster
  {
  /**
   * Displayable name
   * @return string
   */
  public function getName();

  /**
   * @return int Cluster ID
   */
  public function getClusterId();

  /**
   * @param $command_id
   * @return array
   * @throws MuniZigbeeException If there is no command with that ID for this cluster
   */
  public function getClusterSpecificCommand($command_id);
  }