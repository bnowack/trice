<?php

/* Set the namespace. */
namespace trice;

/* Register the Trice autoloader. */
spl_autoload_register(array('trice\Trice', 'autoload'));

/**
 * Trice Core (static for global access).
 * 
 * Acts as autoloader, front controller, singleton registry, ...
 * 
 * Logs used: autoload_error
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class Trice {

  /* instance registry for (named) singletons */
  protected static $singletonRegistry = array();
  /* request object */
  protected static $request = null;
  /* response object */
  protected static $response = null;
  /* command queue */
  protected static $commandQueue = null;
  
  /* Disable instantiation. */
  protected function __construct() {}
  
  /* Disable cloning. */
  private function __clone() {}
  
  /**
   * Locates and loads the class file associated with the referenced class.
   * 
   * @param string $className 
   */
  public static function autoload($className) {
    $path = TRICE_ROOT_PATH . 'code/' . str_replace(array('\\', '_'), '/', $className) . '.php';
    if (file_exists($path)) {
      require($path);
    }
    else {
      //throw new Exception("Cannot autoload '{$className}' ('{$path}').");
      self::log("Could not autoload '{$className}' ('{$path}').", 'autoload_error');
    }
  }
  
  /**
   * Returns an object instance from a local singleton registry (sort-of).
   * 
   * Supports multiple instances of a class through an $instanceName identifier,
   * i.e. the instances are not necessarily singletons.
   * 
   * Arguments are passed to the constructor (once) at instantiation time.
   * 
   * @param string $className
   * @param string $instanceName
   * @param array $args
   * @return object 
   */
  public static function getRegistryInstance($className, $instanceName = '', $args = array()) {
    if (!is_array($args)) {
      $args = array($args);
    }
    $identifier = "{$className} {$instanceName}";
    /* request for a not yet registered object */
    if (!isset(self::$singletonRegistry[$identifier])) {
      /* check class, trigger autoload */
      if (!class_exists($className, true)) {
        throw new Exception("Class {$className} does not exist in getRegistryInstance().");
      }
      /* no arguments, no reflection needed */
      if (empty($args) || !method_exists($className, '__construct')) {
        $instance = new $className();
      }
      /* with arguments, use reflection for the instantiation */
      else {
        $rc = new \ReflectionClass($className);
        $instance = $rc->newInstanceArgs($args);
      }
      /* add the instance to the registry */
      self::$singletonRegistry[$identifier] = $instance;
    }
    /* return the registered instance */
    return self::$singletonRegistry[$identifier];
  }
  
  /**
   * Adds messages to the log identified by $logName.
   * 
   * The log class name and log-specific parameters have to be specified
   * in the trice.ini's log section, e.g. for a $logName "fatal_error":
   *  [log]
   *  fatal_error = "vendor\log\EmailLog"
   *  fatal_error_email = "log@example.com"
   * 
   * @param type $message
   * @param type $logName 
   */
  static public function log($message, $logName) {
    /* Retrieve the log class name. */
    $className = Configuration::get("log/{$logName}");
    /* Ignore non-defined/disabled log operations. */
    if (!$className) {
      return;
    }
    /* Instantiate the log */
    $log = self::getRegistryInstance($className, $logName, $logName);
    /* Log the message */
    $log->add($message);
  }
  
  /**
   * Returns a request singleton.
   * 
   * @return Request
   */
  static public function getRequest() {
    if (self::$request === null) {
      $className = Configuration::get('trice/request_class', 'trice\Request');
      self::$request = self::getRegistryInstance($className);
    }
    return self::$request;
  }
  
  /**
   * Returns a response singleton.
   * 
   * @return Response
   */
  static public function getResponse() {
    if (self::$response === null) {
      $className = Configuration::get('trice/response_class', 'trice\Response');
      self::$response = self::getRegistryInstance($className);
    }
    return self::$response;
  }
  
  /**
   * Returns a command queue singleton.
   * 
   * @return CommandQueue
   */
  static public function getCommandQueue() {
    if (self::$commandQueue === null) {
      $className = Configuration::get('trice/command_queue_class', 'trice\CommandQueue');
      self::$commandQueue = self::getRegistryInstance($className);
    }
    return self::$commandQueue;
  }
  
  /**
   * Dispatches the request and processes the command queue.
   */
  static public function handleRequest() {
    try {
      /* request object */
      $request = self::getRequest();
      /* response object */
      $response = self::getResponse();
      /* command queue object */
      $queue = self::getCommandQueue();
      /* process the queue */
      foreach ($queue as $commandClassName) {
        $queue->processCommand($request, $response);
      }
      $response->buildResult($request) // may still tweak headers
               ->sendHeaders($request)
               ->sendResult($request);
    }
    catch (Exception $e) {
      $e->handleException();
    }
  }
   
}
