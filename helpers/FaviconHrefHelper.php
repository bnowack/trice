<?php

namespace trice\helpers;

use \trice\Trice as Trice;
use \phweb\Configuration as Configuration;

/**
 * Favicon HREF Helper.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class FaviconHrefHelper {
	
	/**
	 * Returns the relative (default) or absolute path to the favicon image.
	 * * @see \trice\Helper::run()
	 */
	public function run($useAbsBase = false) {
		$response = Trice::getResponse();
		$request = Trice::getRequest();
		/* check the layout dir for favicons */
		$formats = array('gif', 'png', 'ico');
		$layout = Configuration::get('app/layout', 'system');
		foreach ($formats as $format) {
			$path = "layouts/{$layout}/favicon.{$format}";
			if (file_exists($path)) {
			$baseArg = $useAbsBase ? 'absBase' : 'relBase';
			return $request->get($baseArg, 'computed') . $path;
			}
		}
	}
	
}
