<?php

namespace trice\bots;

use \trice\Trice as Trice;
use \phweb\Configuration as Configuration;
use \phweb\Request as Request;
use \trice\Response as Response;

/**
 * Default controller for bot operations. Detects and calls associated sub-controllers.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class BotController extends \trice\Controller {
	
	/**
	 * @see \trice\Controller::run()
	 */
	public function run(Request $request, Response $response) {
	$subControllers = array(
		// root request: ping the master bot to make sure it's running.
		'trice\bots\MasterStarterController ^$',
		// master bot requests
		'trice\bots\MasterController ^bots/master',
		// root request or registry requests => registry
		'trice\bots\RegistryController /^bots(\/?$|\/registry\/?)/',
	);
	$queue = Trice::getControllerQueue();
	foreach ($subControllers as $subController) {
		$queue->processController($request, $response, $subController);
	}
	}
	
}
