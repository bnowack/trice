<?php

namespace trice\helpers;

use \trice\Trice as Trice;
use \trice\Configuration as Configuration;

/**
 * Meta-Tags Helper.
 * Generates <meta /> markup.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class MetaTagsHelper {
  
  /**
   * Returns meta-tags including RDFa property attributes if the name is a QName.
   * 
   * @see \trice\Helper::run()   * 
   */
  public function run() {
    $r = '';
    $response = Trice::getResponse();
    $els = $response->getMetaElements();
    foreach ($els as $name => $content) {
      $nameAttribute = strpos($name, ':') ? 'property' : 'name';
      $r .= "\n    <meta {$nameAttribute}=\"{$name}\" content=\"{$response->html($content)}\"/>";
    }
    return $r;
  }
  
}
