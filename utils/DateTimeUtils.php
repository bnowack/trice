<?php

namespace trice\utils;

/**
 * Trice Date/Time utilities class
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class DateTimeUtils {
  
  /**
   * Returns the UTC-normalised unix timestamp. 
   * @return int 
   */
  static public function getUtcUts($uts = null) {
    /* unix timestamp */
    if ($uts == null) {
      $uts = time();
    }
    return $uts - date('Z', $uts);
  }
  
  /**
   * Returns the XSD date or dateTime value for the given Unix timestamp. 
   * @return string 
   */
  static public function getUtcXsd($uts = null, $withTime = false, $isUtc = false) {
    /* unix timestamp */
    if ($uts == null) {
      $uts = time();
    }
    /* convert to UTC, if necessary */
    if (!$isUtc) {
      $uts = self::getUtcUts($uts);
    }
    /* include time */
    if ($withTime) {
      return date('Y-m-d\TH:i:s\Z', $uts);
    }
    /* just the date */
    return date('Y-m-d', $uts);
  }
  
}
