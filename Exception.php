<?php

namespace trice;

/**
 * Generic Trice Exception.
 * 
 * Logs used: error
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class Exception extends \Exception {
  
  /**
   * Handles the exception by calling a message-generating method.
   */
  public function handleException() {
    /* Retrieve "exception_reporting" mode (full|short|no). */
    $mode = Configuration::get('trice/exception_reporting');
    /* Make sure the report mode is valid. */
    if (!preg_match('/^(full|short|no)$/', $mode)) {
      $mode = 'full';
    }
    /* Call the error message method. */
    $method = 'generate' . ucfirst($mode) . 'Message';
    $this->$method();
    /* Stop script processing in case of an error */
    exit;
  }
  
  /**
   * "Generates" an empty exception message.
   */
  protected function generateNoMessage() {
  }
  
  /**
   * Generates a compact exception message.
   */
  protected function generateShortMessage() {
    $file = $this->getFile();
    $line = $this->getLine();
    Trice::log("Error in {$file}({$line}): '" . $this->getMessage() . "'", 'error');
  }
  
  /**
   * Generates a full exception message.
   */
  protected function generateFullMessage() {
    $file = $this->getFile();
    $line = $this->getLine();
    Trice::log("Error in {$file}({$line}): '" . $this->__toString() . "'", 'error');
  }
  
}
