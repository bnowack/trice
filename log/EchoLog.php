<?php


namespace trice\log;

/**
 * Trice EchoLog.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class EchoLog implements Log {
  
  /**
   * @see \trice\Log
   */
  public function add($message) {
    if (!headers_sent()) {
      header('Content-Type: text/plain');
    }
    echo "\n{$message}";
  }
  
}
