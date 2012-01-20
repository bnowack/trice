<?php

namespace trice\web;

use \trice\Trice as Trice;

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
	public static function isApplicable() {
		return true;
	}
	
	/**
	 * @see \trice\Controller::run()
	 */
	public function run() {
		$response->setNamespace('og', 'http://ogp.me/ns#');
		//$response->set('metaRdfa', true);
	}
	
	
}
