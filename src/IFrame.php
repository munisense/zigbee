<?php

namespace Munisense\Zigbee;

interface IFrame {

  /**
   * Returns the frame as a sequence of bytes.
   *
   * @return string $frame
   */
  function getFrame();

  /**
   * @param string $frame
   */
  function setFrame($frame);

  function displayFrame();
}