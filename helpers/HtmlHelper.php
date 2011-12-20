<?php

namespace trice\helpers;

use \trice\Trice as Trice;
use \phweb\Configuration as Configuration;

/**
 * HTML Helper.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class HtmlHelper {
	
	/**
	 * Returns the relative (default) or absolute path to the favicon image.
	 * * @see \trice\Helper::run()
	 */
	public function run($value) {
	return htmlspecialchars($value);
	}
	
}
