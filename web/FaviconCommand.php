<?php

namespace trice\web;

use \trice\Trice as Trice;
use \trice\Configuration as Configuration;
use \trice\Request as Request;
use \trice\Response as Response;

/**
 * Favicon command for efficient handling of favicon requests by browsers.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class FaviconCommand implements \trice\Command {
  
  /**
   * @see \trice\Command::isApplicable()
   */
  public static function isApplicable(Request $request, Response $response) {
    if ($response->get('isComplete')) {
      return false;
    }
    return true;
  }
  
  /**
   * @see \trice\Command::run()
   */
  public function run(Request $request, Response $response) {
    /* default: 404 */
    $response->setStatus(404);
    /* use the favicon helper to find the img file */
    $href = $response->faviconHref(true);
    if ($href) {
      $response->setStatus(302);
      $response->setHeader('Location', $href);
      $response->set('result', '<a href="' . $href . '">Found</a>');
    }
    $response->set('isComplete', true);
  }
  
}
