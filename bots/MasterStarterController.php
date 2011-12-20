<?php

namespace trice\bots;

use \trice\Trice as Trice;
use \phweb\Configuration as Configuration;
use \phweb\Request as Request;
use \trice\Response as Response;
use \phweb\Stream as Stream;

/**
 * Master bot starter controller.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class MasterStarterController extends \trice\Controller {
	
	/**
	 * Starts the master bot if it isn't running yet.
	 * 
	 * @see \trice\Controller::run()
	 */
	public function run(Request $request, Response $response) {
	$url = $request->get('abs_base') . 'bots/master/start';
	Trice::log("Calling {$url}", 'bots');
	$stream = new Stream($url);
	$stream->ping();
	}
	
}
