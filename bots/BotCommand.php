<?php

namespace trice\bots;

use \trice\Trice as Trice;
use \trice\Configuration as Configuration;
use \trice\Request as Request;
use \trice\Response as Response;

/**
 * Default command for bot operations. Detects and calls associated sub-commands.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class BotCommand extends \trice\Command {
  
  /**
   * @see \trice\Command::run()
   */
  public function run(Request $request, Response $response) {
    $parts = $request->get('path_parts');
    $path = $parts[0];
    $commands = array(
      'trice\bots\MasterInvokerCommand ^' . $path . '/invoker',
      'trice\bots\MasterCommand ^' . $path . '/master',
      'trice\bots\RegistryCommand ^' . $path . '/?$',
    );
    $queue = Trice::getCommandQueue();
    foreach ($commands as $command) {
      $queue->processCommand($request, $response, $command);
    }
    $response->set('pageTitle', 'Bots');
    $response->set('isComplete', true);
  }
  
}
