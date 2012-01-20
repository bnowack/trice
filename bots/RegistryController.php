<?php

namespace trice\bots;

use \trice\Trice as Trice;

/**
 * Bot registry controller.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class RegistryController extends \trice\Controller {
	
	/**
	 * @see \trice\Controller::run()
	 */
	public function run() {
		/* Apply the default HTML Document controller */
		Trice::getControllerQueue()->processController('trice\web\HtmlDocumentController');
		$parts = Trice::getRequest()->get('pathParts');
		$path = $parts[0];
		$response->set('pageTitle', 'Registry');
		$response->set('isComplete', true);
	}
	
}
