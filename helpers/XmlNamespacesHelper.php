<?php

namespace trice\helpers;

use \trice\Trice as Trice;
use \trice\Configuration as Configuration;

/**
 * XML Namespaces Helper.
 * Generates xmlns markup.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class XmlNamespacesHelper {
  
  /**
   * Returns xmlns:prefix= or xmlns= markup from defined namespaces
   * 
   * @see \trice\Helper::run()   * 
   */
  public function run() {
    $r = '';
    $response = Trice::getResponse();
    $namespaces = $response->getNamespaces();
    foreach ($namespaces as $prefix => $uri) {
      $prefix = $prefix ? 'xmlns:' . $prefix : 'xmlns';
      $r .= ($r ? ' ' : '') . $prefix . '="' . $uri . '"';
    }
    return $r;
  }
  
}
