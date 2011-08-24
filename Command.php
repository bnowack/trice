<?php

namespace trice;

use \trice\Request as Request;
use \trice\Response as Response;

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
  
}
