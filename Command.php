<?php

namespace trice;

use \trice\Request as Request;
use \trice\Response as Response;

/**
 * Command interface.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
interface Command {
  
  /**
   * Checks whether the command applies to the given request.
   * 
   * @param Request $request
   * @param Response $response
   * @return bool 
   */
  public static function isApplicable(Request $request, Response $response);
  
  /**
   * Executes the command.
   *    
   * @param Request $request
   * @param Response $response
   */
  public function run(Request $request, Response $response);
  
}
