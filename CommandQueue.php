<?php

/**
 * Trice request object.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */

namespace trice;

use \trice\Trice as Trice;
use \trice\Exception as Exception;
use \trice\Configuration as Configuration;

class CommandQueue implements \Iterator {
  
  // class variables
  protected $queue;
  protected $pos;
  protected $queueLength;

  public function __construct() {
    $this->initializeQueue();
  }
  
  /**
   * Sets the queue position to 0
   */
  public function rewind() {
    $this->pos = 0;
  }
  
  /**
   * Returns the current position
   */
  public function key() {
    return $this->pos;
  }
  
  /**
   * Moves the queue position by one.
   */
  public function next() {
    $this->pos++;
  }
  
  /**
   * Checks if the current position is valid.
   */
  public function valid() {
    return ($this->pos < $this->queueLength);
  }
  
  public function current() {
    return $this->getCommand($this->pos);
  }
  
  /**
   * Initialises the command queue.
   */
  protected function initializeQueue() {
    /* Get log path via $logName. */
    $this->queue = Configuration::get('app/commands', array());
    $this->rewind();
    $this->queueLength = count($this->queue);
  }
  
  /**
   * Resets the command queue.
   */
  public function resetQueue() {
    $this->queue = array();
    $this->rewind();
    $this->queueLength = count($this->queue);
  }

  /**
   * Adds a command class to the queue at the provided position or the end.
   * 
   * @param string $className
   * @param int $pos 
   */
  public function addCommand($className, $pos = null) {
    if (($pos === null) || ($pos > $this->queueLength)) {
      $pos = $this->queueLength;
    }
    array_splice($this->queue, $pos, 0, $className);
    $this->queueLength = count($this->queue);
  }
  
  /**
   * Returns a command from the queue
   */
  public function getCommand($pos) {
    if (!isset($this->queue[$pos])) {
      throw new Exception("Invalid queue key '{$pos}'.");
    }
    return $this->queue[$pos];
  }
  
  /**
   * Runs the iterators current command or the one specified in $className.
   * 
   * @param Request $request
   * @param Response $response
   * @param string $className
   * @return CommandQueue 
   */
  public function processCommand($request, $response, $className = null) {
    if ($className === null) {
      $className = $this->current();
    }
    $pathMatch = '';
    if (preg_match('/^([^\s]+)\s+(.+)$/', $className, $m)) {
      $className = $m[1];
      $pathMatch = $m[2];
    }
    /* non-matching path match */
    if ($pathMatch && !$request->pathMatches($pathMatch)) {
      return $this;
    }
    /* non-implemented command class */
    if (!class_exists($className, true)) {
      return $this;
    }
    /* non-applicable command */
    if (!$className::isApplicable($request, $response)) {
      return $this;
    }
    /* instantiate and execute the command */
    $command = new $className();
    $command->run($request, $response);
    return $this;
  }
 
  
}
