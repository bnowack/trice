<?php

namespace trice\bots;

use \trice\Trice as Trice;
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
	public function run() {
		$url = Trice::getRequest()->get('absBase') . 'bots/master/start';
		Trice::log("Calling {$url}", 'bots');
		$stream = new Stream($url);
		$stream->ping();
	}
	
}
