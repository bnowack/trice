<?php

namespace trice\web;

use \trice\Trice as Trice;
use \phweb\Configuration as Configuration;
use \trice\Exception as Exception;
use \phweb\Request as Request;
use \trice\Response as Response;

/**
 * OGP Document Controller. 
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class OgpDocumentController extends \trice\Controller {
	
	/**
	 * @see \trice\Controller::isApplicable()
	 */
	public static function isApplicable(Request $request, Response $response) {
	return true;
	}
	
	/**
	 * @see \trice\Controller::run()
	 */
	public function run(Request $request, Response $response) {
	$response->setNamespace('og', 'http://ogp.me/ns#');
	//$response->set('metaRdfa', true);
	}
	
	
}
