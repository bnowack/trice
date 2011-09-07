<?php

namespace trice;

/**
 * Stream class for HTTP and other stream operations.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class Stream {

  protected $location = null;
  protected $type = null;
  protected $options = array();
  protected $callbacks = array();
  protected $context = null;
  protected $connection = null;
  
  /**
   * Stream constructor with optional $location (a path or URI) and $type.
   * 
   * @param string $location 
   */
  public function __construct($location = null, $type = 'http') {
    $this->location = $location;
    $this->type = $type;
    // initialise the stream options
    $this->options = array(
      'method' => 'GET',
      'timeout' => 10.0,
      'content' => null,
      'protocol_version' => 1.1,
      'follow_location' => true,  // false available in php5.3.4
      'max_redirects' => 5,
      'ignore_errors' => true,
      'proxy' => null
    );
    $this->headers = array(
      'Accept' => '*/*', // application/foo; q=0.9, application/bar; q=0.1
      'User-Agent' => 'Trice',
    );
  }
  
  /**
   * Sets the stream type (e.g. "http")
   * 
   * @param string $type
   * @return Stream
   */
  public function setType($type) {
    $this->type = $type;
    return $this;
  }
  
  /**
   * Sets the stream's target location
   * 
   * @param string $location
   * @return Stream
   */
  public function setLocation($location) {
    $this->location = $location;
    return $this;
  }
  
  /**
   * Sets a stream option.
   * 
   * @param string $name
   * @param string $value
   * @return Stream
   */
  public function setOption($name, $value) {
    // pre-connect
    if ($this->context === null) {
      $this->options[$name] = $value;
    }
    // post-connect
    else {
      stream_context_set_option($this->context, $this->type, $name, $value);
    }
    return $this;
  }
  
  public function addCallback($obj, $method) {
    $this->callbacks[] = array($obj, $method);
  }
  
  /**
   * Sets a stream header.
   * 
   * @param string $name
   * @param string $value
   * @return Stream
   */
  public function setHeader($name, $value) {
    $this->headers[$name] = $value;
    return $this;
  }
  
  /**
   * Generates and returns a single header string from the headers array.
   *  
   * @return string
   */
  protected function getHeaderString() {
    $result = '';
    $n = "\r\n";
    foreach ($this->headers as $name => $value) {
      $result .= "{$name}: {$value}{$n}";
    }
    return $result;
  }
  
  /**
   * Establishes the stream connection using the given connection method (e.g. "wb").
   * When $pingOnly is true, no exception will be thrown.
   * 
   * @param string $mode
   * @return Stream
   */
  public function connect($mode = 'r') {
    if ($this->connection === null) {
      // Check if location is set.
      if (!$this->location) {
        throw new Exception('Location not set in trice\Stream::getConnection');
        return false;
      }
      // Create the context.
      if (!$this->context) {
        $this->createContext();
      }
      $this->connection = @fopen($this->location, $mode, false, $this->context);
      if (!$this->connection && !$this->options['ignore_errors']) {
        throw new Exception('Could not connect to "' . $this->location . '" in trice\Stream::getConnection');
        return false;
      }
      Trice::log("Connecting to {$this->location}", 'stream');
    }
    return $this;
  }
  
  /**
   * Creates a context object from local stream options and parameters.
   */
  public function createContext() {
    // header string
    $this->options['header'] = $this->getHeaderString();
    // context object
    $this->context = stream_context_create(array($this->type => $this->options));
    // callback
    stream_context_set_params($this->context, array('notification' => array($this, 'onStreamEvent')));
    return $this;
  }
  
  /**
   * Forwards stream notofocation events to defined stream callbacks.
   * 
   * @param type $notification_code
   * @param type $severity
   * @param type $message
   * @param type $message_code
   * @param type $bytes_transferred
   * @param type $bytes_max 
   */
  public function onStreamEvent($notification_code, $severity, $message, $message_code, $bytes_transferred, $bytes_max) {
    //echo microtime(true) . " | {$notification_code} | {$bytes_transferred} | {$message_code} | {$message} | {$bytes_transferred} \n";
    $args = func_get_args();
    foreach ($this->callbacks as $callback) {
      call_user_func_array($callback, $args);
    }
  }
  
  /**
   * Pings a location by opening a stream and instantly closing it again.
   */
  public function ping() {
    $this->setOption('timeout', 0.01);
    $this->connect();
    $this->close();
  }
  
  public function getMetaData() {
    $result = array();
    if ($this->connection !== false) {
      $result = stream_get_meta_data($this->connection);
    }
    return $result;
  }
  
  public function close() {
    if ($this->connection !== false) {
      fclose($this->connection);
      unset($this->connection);
    }
  }
  
}
