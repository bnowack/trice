<?php

namespace trice\bots;

use \trice\Trice as Trice;
use \phweb\Configuration as Configuration;
use \phweb\Request as Request;
use \trice\Response as Response;

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
	public function run(Request $request, Response $response) {
	/* Apply the default HTML Document controller */
	Trice::getControllerQueue()->processController($request, $response, 'trice\web\HtmlDocumentController');
	$parts = $request->get('path_parts');
	$path = $parts[0];
	$response->set('pageTitle', 'Registry');
	$response->set('isComplete', true);
	}
	
}
