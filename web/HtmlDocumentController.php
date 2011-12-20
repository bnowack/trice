<?php

namespace trice\web;

use \trice\Trice as Trice;
use \phweb\Configuration as Configuration;
use \trice\Exception as Exception;
use \phweb\Request as Request;
use \trice\Response as Response;

/**
 * HTML Document Controller. 
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class HtmlDocumentController extends \trice\Controller {
	
	/**
	 * @see \trice\Controller::run()
	 */
	public function run(Request $request, Response $response) {
		$response->setHeader('Content-Type', 'text/html; charset=utf-8');
		//$response->setNamespace('', 'http://www.w3.org/1999/xhtml');
		$response->setMeta('robots', 'index, follow');
		$response->setLink('shortcut icon', $response->faviconHref());
		$response->addScript('jquery/jquery.js');
		$response->addScript('trice/trice.js');
	}
	
}
