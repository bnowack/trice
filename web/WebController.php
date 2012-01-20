<?php

namespace trice\web;

use \trice\Trice as Trice;

/**
 * Default controller for web applications. Calls associated sub-controllers.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class WebController extends \trice\Controller {
	
	/**
	 * @see \trice\Controller::run()
	 */
	public function run() {
		$subControllers = array(
			// Handle favicon requests.
			'trice\web\FaviconController ^favicon.ico$',
			// Handle css requests.
			'trice\web\CssController ^css/',
			// Handle script requests.
			'trice\web\JavaScriptController ^js/',
		);
		$queue = Trice::getControllerQueue();
		foreach ($subControllers as $subController) {
			$queue->processController($subController);
		}
	}
	
}
