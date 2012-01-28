<?php
/**
 * Trice request catcher.
 * 
 * Should be included (e.g. via require_once('code/trice/index.php')) from the app's root "index.php".
 * All requests should then be routed to the root "index.php", e.g. via mod_rewrite.
 */

// Ensure time() is E_STRICT-compliant (optional, if specified in php.ini)
if (function_exists('date_default_timezone_get')) {
	date_default_timezone_set(@date_default_timezone_get());
}

// Load PhWeb and its autoloader
$phWebDir = rtrim(realpath("code/phweb"), '/') . '/';
require_once("{$phWebDir}PhWeb.php");

// Load Trice and its autoloader
$triceDir = rtrim(realpath("code/trice"), '/') . '/';
require_once("{$triceDir}Trice.php");

// Define the path to the configuration file (can be relative)
defined('TRICE_INI_PATH') || define('TRICE_INI_PATH', 'config/trice.ini');

// Define the path to the code directory (can be relative)
defined('TRICE_INCLUDE_DIR') || define('TRICE_INCLUDE_DIR', 'code/');

// Let Trice take it from here
trice\Trice::handleRequest();
