<?php

namespace trice;

use \phweb\utils\StringUtils as StringUtils;

/**
 * Abstract Controller class.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
abstract class Controller {
	
	/**
	 * Checks whether the controller applies to the given request.
	 * 
	 * @param Request $request
	 * @param Response $response
	 * @return bool 
	 */
	public static function isApplicable() {
		return Trice::getResponse()->get('isComplete') ? false : true;
	}
	
	/**
	 * Executes the controller.
	 *	
	 * @param Request $request
	 * @param Response $response
	 */
	abstract public function run();
	
	/**
	 * Returns a controller method name based on a request path portion.
	 * 
	 * e.g. given "/sys/users":
	 *	$level == 0 : handleSysCall
	 *	$level == 1 : handleUsersCall
	 * 
	 * @param Request $request
	 * @param int $level
	 * @return mixed 
	 */
	public function getCall($level = 0) {
		$request = Trice::getRequest();
		$parts = $request->get('pathParts');
		if (($level == 0) && ($parts[0] == '')) return 'handleHomeCall';
		if (isset($parts[$level]) && $parts[$level]) {
			return 'handle' . StringUtils::camelCase($parts[$level]) . 'Call';
		}
		return false;
	}
	
	/**
	 * Calls a path-derived controller method.
	 * 
	 * @param Request $request
	 * @param int $level
	 * @return bool
	 */
	public function handleCall($level = 0, $defaultCall = null) {
		$method = $this->getCall($level);
		if ($method && method_exists($this, $method)) {
			$this->$method($level);
			return true;
		}
		elseif ($defaultCall && method_exists($this, $defaultCall)) {
			$this->$defaultCall($level);
			return true;
		}
		return false;
	}
	
	/**
	 * Triggers a 501 response.
	 * 
	 * @param type $level 
	 */
	public function handleNotImplementedCall($level = 0) {
		Trice::getResponse()->notImplemented();
	}
	
	/**
	 * Triggers a 501 response.
	 * 
	 * @param type $level 
	 */
	public function handleNotFoundCall($level = 0) {
		Trice::getResponse()->notFound();
	}
	
}
