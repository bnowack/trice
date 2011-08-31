<?php

namespace trice\utils;

/**
 * Trice string utilities class
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class StringUtils {

  /**
   * Generates a camelCase string from $value.
   * 
   * @param string $value
   * @return string
   */
  static public function camelCase($value, $lcfirst = false) {
    $result = ucfirst($value);
    while (preg_match('/^(.+)[^a-z0-9](.+)$/si', $result, $m)) {
      $result = $m[1] . ucfirst($m[2]);
    }
    if ($lcfirst) {
      $result = lcfirst($result);
    }
    return $result;
  }
  
}

