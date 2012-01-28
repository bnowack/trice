<?php

/* Set the namespace. */
namespace trice;

use \phweb\PhWeb as PhWeb;
use \phweb\Configuration as Configuration;

/* Register the Trice autoloader. */
spl_autoload_register(array('trice\Trice', 'autoload'), true, true);

// Make sure the Trice code directory is available.
defined('TRICE_DIR') || define('TRICE_DIR', rtrim(dirname(__FILE__), '/') . '/');

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

	protected static $singletonRegistry = array();
	protected static $request = null;
	protected static $response = null;
	protected static $controllerQueue = null;
	protected static $autoLoaded = array();

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
		if (isset(self::$autoLoaded[$className])) return;
		$dirs = array(
			TRICE_DIR,
		);
		if (defined('TRICE_INCLUDE_DIR')) {
			$dirs[] = TRICE_INCLUDE_DIR;
		}
		$classPath = str_replace(array('\\', '_'), '/', $className) . '.php';
		$paths = array(
			$classPath,
			str_replace('trice/', '', $classPath),
		);
		$found = false;
		foreach ($dirs as $dir) {
			foreach ($paths as $path) {
				if (file_exists("$dir$path")) {
					require_once("$dir$path");
					$found = 1;
					break;
				}
			}
			if ($found) break;
		}
		self::$autoLoaded[$className] = true;
	}
	
	public static function getConfiguration($name = '', $default = null) {
		return $name ? Configuration::get($name, $default) : Configuration;
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
	 * @param mixed $args
	 * @return object 
	 */
	public static function getRegistryInstance($className, $instanceName = '', $args = array()) {
		if (!is_array($args)) {
			$args = array($args);
		}
		$identifier = "{$className} {$instanceName}";
		// request for a not yet registered object
		if (!isset(self::$singletonRegistry[$identifier])) {
			// check class, trigger autoload
			if (!class_exists($className, true)) {
				throw new Exception("Class {$className} does not exist in getRegistryInstance().");
			}
			// no arguments, no reflection needed
			if (empty($args) || !method_exists($className, '__construct')) {
				$instance = new $className();
			}
			// with arguments, use reflection for the instantiation
			else {
				$rc = new \ReflectionClass($className);
				$instance = $rc->newInstanceArgs($args);
			}
			// add the instance to the registry
			self::$singletonRegistry[$identifier] = $instance;
		}
		// return the registered instance
		return self::$singletonRegistry[$identifier];
	}
	
	/**
	 * Adds messages to the log identified by $logName.
	 * 
	 * The log class name and log-specific parameters have to be specified
	 * in the trice.ini's log section, e.g. for a $logName "fatal_error":
	 *	[log]
	 *	fatal_error = "vendor\log\EmailLog"
	 *	fatal_error_email = "log@example.com"
	 * 
	 * @param type $message
	 * @param type $logName 
	 */
	static public function log($message, $logName) {
		// Retrieve the log class name.
		$className = Configuration::get("log/{$logName}");
		// Ignore non-defined/disabled log operations.
		if (!$className) {
			return;
		}
		// Instantiate the log
		$log = self::getRegistryInstance($className, $logName, $logName);
		// Log the message
		$log->add($message);
	}
	
	/**
	 * Returns a request singleton.
	 * 
	 * @return Request
	 */
	static public function getRequest() {
		return PhWeb::getRequest();
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
	 * Returns a controller queue singleton.
	 * 
	 * @return ControllerQueue
	 */
	static public function getControllerQueue() {
		if (self::$controllerQueue === null) {
			$className = Configuration::get('trice/controller_queue_class', 'trice\ControllerQueue');
			self::$controllerQueue = self::getRegistryInstance($className);
		}
		return self::$controllerQueue;
	}
	
	static public function getSession() {
		return self::getRegistryInstance('trice\session\Session');
	}
	
	static public function getSessionId() {
		$session = self::getSession();
		return $session->getId();
	}
	
	static public function persistSession() {
		$session = self::getSession();
		$session->persist();
	}
	
	/**
	 * Dispatches the request and processes the controller queue.
	 */
	static public function handleRequest() {
		try {
			// set the conf path
			Configuration::setPath(TRICE_INI_PATH);
			// request object
			$request = self::getRequest();
			// response object
			$response = self::getResponse();
			// controller queue object
			$queue = self::getControllerQueue();
			// process the queue
			foreach ($queue as $controllerClassName) {
				$queue->processController();
			}
			// 404
			if (!$response->get('isComplete')) {
				$response->notFound();
			}
			$response->buildResult($request) // may still tweak headers
					 ->sendHeaders($request)
					 ->sendResult($request);
			self::persistSession();
		}
		catch (Exception $e) {
			$e->handleException();
		}
	}
	 
}
