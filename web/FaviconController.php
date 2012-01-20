<?php

namespace trice\web;

use \trice\Trice as Trice;
use \phweb\Configuration as Configuration;

/**
 * Favicon controller for efficient handling of "/favicon.ico" GETs by browsers.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class FaviconController extends \trice\Controller {
	
	/**
	 * @see \trice\Controller::run()
	 */
	public function run() {
		$resp = Trice::getResponse();
		// Use the favicon helper to find the img file.
		$href = $resp->faviconHref(true);
		// Redirect client to favicon file if one exists.
		if ($href) {
			$resp->setStatus(302)
				->setHeader('Location', $href)
				->set('result', '<a href="' . $href . '">Found</a>');
		}
		// Default: 404
		else {
			$resp->setStatus(404);
		}
		// Prevent further controller processing.
		$resp->set('isComplete', true);
	}
	
}
