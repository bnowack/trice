<?php

/**
 * Global Trice Configuration (static).
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */

namespace trice;

class Configuration {
  
  // class variables
  protected static $conf = null;
  protected static $confPath = 'config/trice.ini';

  // disable instantiation and cloning
  protected function __construct() {}
  private function __clone() {}
  
  
  /**
   * Initializes (if not done yet) and returns the configuration array.
   * 
   * Section names are converted to option prefixes (e.g. php/error_reporting)
   * 
   * @return array the configuration array extracted from self::$confPath
   */
  protected static function getConfiguration() {
    if (self::$conf == null) {
      self::$conf = array();
      if (file_exists(self::$confPath)) {
        self::$conf = parse_ini_file(self::$confPath, true);
        foreach (self::$conf as $section => $options) {
          foreach ($options as $name => $value) {
            self::$conf["{$section}/{$name}"] = $value;
          }
          unset(self::$conf[$section]);
        }
      }
    }
    return self::$conf;
  }
  
  /**
   * Returns a single configuration option.
   * 
   * @param string $name
   * @param mixed $default
   * @return mixed 
   */
  public static function get($name, $default = null) {
    $conf = self::getConfiguration();
    return isset($conf[$name]) ? $conf[$name] : $default;
  }
  
}
