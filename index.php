<?php
/**
 * Trice request catcher.
 * 
 * Should be symlink'd from (or copied to) the app's root directory.
 * All requests should then be routed to the root index.php, e.g. via mod_rewrite.
 * 
 */

// Code include paths (relative)
defined('TRICE_DIR') || define('TRICE_DIR', 'code/trice/');
defined('PHWEB_DIR') || define('PHWEB_DIR', 'code/phweb/');

// Configuration file (relative)
defined('TRICE_INI_PATH') || define('TRICE_INI_PATH', 'config/trice.ini');

/* Ensure time() is E_STRICT-compliant (optional, if specified in php.ini) */
if (function_exists('date_default_timezone_get')) {
	date_default_timezone_set(@date_default_timezone_get());
}

/* Load PhWeb and its autoloader */
require_once(PHWEB_DIR . 'PhWeb.php');

/* Load Trice and its autoloader */
require_once(TRICE_DIR . 'Trice.php');

/* Let Trice take it from here */
trice\Trice::handleRequest();
