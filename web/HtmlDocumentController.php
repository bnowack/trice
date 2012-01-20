<?php

namespace trice\web;

use \trice\Trice as Trice;

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
	public function run() {
		$resp = Trice::getResponse();
		$resp->setHeader('Content-Type', 'text/html; charset=utf-8')
			//->setNamespace('', 'http://www.w3.org/1999/xhtml')
			->setMeta('robots', 'index, follow')
			->setLink('shortcut icon', $resp->faviconHref())
			->addScript('jquery/jquery.js')
			->addScript('trice/trice.js');
	}
	
}
