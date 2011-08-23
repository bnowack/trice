<?php

namespace trice\helpers;

use \trice\Trice as Trice;
use \trice\Configuration as Configuration;

/**
 * Style-Tags Helper.
 * Generates <style /> markup.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class StyleTagsHelper {
  
  /**
   * Returns style-tags.
   * 
   * @see \trice\Helper::run()   * 
   */
  public function run() {
    $r = '';
    $response = Trice::getResponse();
    $els = $response->get('stylesheet', array());
    foreach ($els as $rel => $href) {
      $r .= "\n    <link rel=\"{$rel}\" href=\"{$response->html($href)}\"/>";
    }
    return $r;
  }
  
}
