<?php
/**
 * Trice request catcher.
 * 
 * Should be symlink'd from (or copied to) the app's root directory.
 * All requests should then be routed to the root index.php, e.g. via mod_rewrite.
 * 
 */

/* App include path (relative) */
define('TRICE_ROOT_PATH', '');

/* Ensure time() is E_STRICT-compliant (optional if specified in php.ini) */
if (function_exists('date_default_timezone_get')) {
  date_default_timezone_set(@date_default_timezone_get());
}

/* Load Trice, all other classes will get autoloaded. */
require_once(TRICE_ROOT_PATH . 'code/trice/Trice.php');

/* Let Trice handle the request */
trice\Trice::handleRequest();

?>