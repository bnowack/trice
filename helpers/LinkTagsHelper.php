<?php

namespace trice\helpers;

use \trice\Trice as Trice;
use \trice\Configuration as Configuration;

/**
 * Link-Tags Helper.
 * Generates <link /> markup.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class LinkTagsHelper {
  
  /**
   * Returns link-tags.
   * 
   * @see \trice\Helper::run()   * 
   */
  public function run() {
    $r = '';
    $response = Trice::getResponse();
    $els = $response->getLinks();
    foreach ($els as $rel => $href) {
      $r .= "\n    <link rel=\"{$rel}\" href=\"{$response->html($href)}\"/>";
    }
    return $r;
  }
  
}
