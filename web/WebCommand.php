<?php

namespace trice\web;

use \trice\Trice as Trice;
use \trice\Configuration as Configuration;
use \trice\Request as Request;
use \trice\Response as Response;

/**
 * Default command for web applications. Calls associated sub-commands.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class WebCommand extends \trice\Command {
  
  /**
   * @see \trice\Command::run()
   */
  public function run(Request $request, Response $response) {
    $commands = array(
      'trice\web\FaviconCommand ^favicon.ico$',
      'trice\web\CssCommand ^css/',
      'trice\web\ScriptCommand ^js/',
    );
    $queue = Trice::getCommandQueue();
    foreach ($commands as $command) {
      $queue->processCommand($request, $response, $command);
    }
  }
  
}
