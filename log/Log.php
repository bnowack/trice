<?php

namespace trice\log;

/**
 * Log interface.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
interface Log {
  
  /**
   * Adds a $message to the log.
   * 
   * @param string $message 
   */
  public function add($message);
  
}
