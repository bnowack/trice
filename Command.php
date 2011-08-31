<?php

namespace trice;

use \trice\Request as Request;
use \trice\Response as Response;
use \trice\utils\StringUtils as StringUtils;

/**
 * Abstract Command class.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
abstract class Command {
  
  /**
   * Checks whether the command applies to the given request.
   * 
   * @param Request $request
   * @param Response $response
   * @return bool 
   */
  public static function isApplicable(Request $request, Response $response) {
    return $response->get('isComplete') ? false : true;
  }
  
  /**
   * Executes the command.
   *    
   * @param Request $request
   * @param Response $response
   */
  abstract public function run(Request $request, Response $response);
  
  /**
   * Returns a command method name based on a request path portion.
   * 
   * e.g. given "/sys/users":
   *  $level == 0 : handleSysCall
   *  $level == 1 : handleUsersCall
   * 
   * @param Request $request
   * @param int $level
   * @return mixed 
   */
  public function getCall(Request $request, $level = 0) {
    $parts = $request->get('path_parts');
    if (isset($parts[$level])) {
      return 'handle' . StringUtils::camelCase($parts[$level]) . 'Call';
    }
    return false;
  }
  
}
