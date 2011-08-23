<?php

namespace trice\web;

use \trice\Trice as Trice;
use \trice\Configuration as Configuration;
use \trice\Exception as Exception;
use \trice\Request as Request;
use \trice\Response as Response;

/**
 * OGP Document Command. 
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class OgpDocumentCommand implements \trice\Command {
  
  /**
   * @see \trice\Command::isApplicable()
   */
  public static function isApplicable(Request $request, Response $response) {
    return true;
  }
  
  /**
   * @see \trice\Command::run()
   */
  public function run(Request $request, Response $response) {
    $response->setNamespace('og', 'http://ogp.me/ns#');
    //$response->set('metaRdfa', true);
  }
  
  
}
