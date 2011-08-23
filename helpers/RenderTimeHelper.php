<?php

namespace trice\helpers;

use \trice\Trice as Trice;
use \trice\Configuration as Configuration;

/**
 * Render time Helper.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class RenderTimeHelper {
  
  /**
   * Returns the time needed to render the current template (via the render() method).
   * * @see \trice\Helper::run()
   */
  public function run($template, $renderStart) {
    if (Configuration::get('trice/hide_render_time', false)) {
      return '';
    }
    return '<!-- ' . round(microtime(true) - $renderStart, 4) . ' (' . $template . ') -->' . "\n";
  }
  
}
