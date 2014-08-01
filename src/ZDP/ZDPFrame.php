<?php

namespace Munisense\Zigbee\ZDP;
use Munisense\Zigbee\AbstractFrame;
use Munisense\Zigbee\Buffer;
use Munisense\Zigbee\Exception\MuniZigbeeException;

class ZDPFrame extends AbstractFrame
  {
  private $command_id = 0xffff;
  private $transaction_id = 0x00;

  private $payload = "";

  public static function construct(IZDPCommandFrame $payload, $transaction_id = 0x00)
    {
    $frame = new self;
    $frame->setPayloadObject($payload);
    $frame->setTransactionId($transaction_id);
    return $frame;
    }

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
    return Command::displayCommand($this->getCommandId());
    }

  public function setPayload($payload)
    {
    $this->payload = $payload;
    }

  public function getPayload()
    {
    return $this->payload;
    }

  public function setPayloadObject(IZDPCommandFrame $object)
    {
    $this->setCommandId($object->getClusterId());
    $this->setPayload($object->getFrame());
    return;
    }

  public function getPayloadObject()
    {
    $class = $this->findClassOfPayload();
    return new $class($this->getPayload());
    }

  protected function findClassOfPayload()
    {
    if(isset(Command::$command[$this->getCommandId()]))
      return Command::$command[$this->getCommandId()]['class'];

    throw new MuniZigbeeException("Payload class not found");
    }

  public function displayPayload()
    {
    return Buffer::displayOctetString($this->getPayload());
    }

  public function __toString()
    {
    $output =  __CLASS__." (length: ".strlen($this->getFrame()).", command: ".$this->displayCommandId().")".PHP_EOL;
    $output .= "|- TransactionId    : ".$this->displayTransactionId().PHP_EOL;
    $output .= "|- Payload (length: ".strlen($this->getPayload()).")".PHP_EOL;

    try
      {
      $output .= preg_replace("/^   /", "`- ", preg_replace("/^/m", "   ", $this->getPayloadObject()));
      }
    catch(\Exception $e)
      {
      $output .= "`-> ".$this->displayPayload().PHP_EOL;
      }

    return $output;
    }
  }

