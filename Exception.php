<?php

namespace trice;

use \phweb\Configuration as Configuration;

/**
 * Generic Trice Exception.
 * 
 * Logs used: error
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class Exception extends \phweb\Exception {
	
	protected function getExceptionReportingMode() {
		return Configuration::get('trice/exception_reporting');
	}
	
}
