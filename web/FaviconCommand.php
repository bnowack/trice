<?php

namespace trice\web;

use \trice\Trice as Trice;
use \trice\Configuration as Configuration;
use \trice\Request as Request;
use \trice\Response as Response;

/**
 * Favicon command for efficient handling of "/favicon.ico" GETs by browsers.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class FaviconCommand extends \trice\Command {
  
  /**
   * @see \trice\Command::run()
   */
  public function run(Request $request, Response $response) {
    // Use the favicon helper to find the img file.
    $href = $response->faviconHref(true);
    // Redirect client to favicon file if one exists.
    if ($href) {
      $response->setStatus(302);
      $response->setHeader('Location', $href);
      $response->set('result', '<a href="' . $href . '">Found</a>');
    }
    // Default: 404
    else {
      $response->setStatus(404);
    }
    // Avoid further command processing.
    $response->set('isComplete', true);
  }
  
}
