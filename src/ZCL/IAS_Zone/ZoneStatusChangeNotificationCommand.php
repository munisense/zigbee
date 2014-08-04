<?php
/**
 * 8.2.2.4.1 Zone Status Change Notification Command
 *
 * The Zone Status Change Notification command is generated when a change takes
 * place in one or more bits of the ZoneStatusattribute.
 */

namespace Munisense\Zigbee\ZCL\IAS_Zone;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\ZCL\IZCLCommandFrame;
use Munisense\Zigbee\ZCL\ZCLFrame;

class ZoneStatusChangeNotificationCommand extends AbstractFrame implements IZCLCommandFrame
  {
  const COMMAND_ID = 0x00;
  const NAME = "Zone Status Change Notification";

  /**
   * @var ZoneStatus $zone_status
   */
  protected $zone_status;

  /**
   * @var int $extended_status
   */
  protected $extended_status = 0x00;

  public static function construct(ZoneStatus $zone_status, $extended_status = 0x00)
    {
    $frame = new self;
    $frame->setZoneStatus($zone_status);
    $frame->setExtendedStatus($extended_status);
    return $frame;
    }

  /**
   * @param int $extended_status
   */
  public function setExtendedStatus($extended_status)
    {
    $this->extended_status = $extended_status;
    }

  /**
   * @return int
   */
  public function getExtendedStatus()
    {
    return $this->extended_status;
    }

  /**
   * @param \Munisense\Zigbee\ZCL\IAS_Zone\ZoneStatus $zone_status
   */
  public function setZoneStatus($zone_status)
    {
    $this->zone_status = $zone_status;
    }

  /**
   * @return \Munisense\Zigbee\ZCL\IAS_Zone\ZoneStatus
   */
  public function getZoneStatus()
    {
    return $this->zone_status;
    }

  /**
   * Returns the frame as a sequence of bytes.
   *
   * @return string $frame
   */
  function getFrame()
    {
    $frame = "";
    Buffer::packInt16u($frame, $this->getZoneStatus()->getValue());
    Buffer::packInt8u($frame, $this->getExtendedStatus());
    return $frame;
    }

  /**
   * @param string $frame
   */
  function setFrame($frame)
    {
    $zone_status = new ZoneStatus();
    $zone_status->setValue(Buffer::unpackInt16u($frame));
    $this->setZoneStatus($zone_status);
    $this->setExtendedStatus(Buffer::unpackInt8u($frame));
    }

  public function getCommandId()
    {
    return self::COMMAND_ID;
    }

  public function getFrameType()
    {
    return ZCLFrame::FRAME_TYPE_CLUSTER_SPECIFIC;
    }
  }