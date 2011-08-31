<?php

namespace trice\web;

use \trice\Trice as Trice;
use \trice\Configuration as Configuration;
use \trice\Exception as Exception;
use \trice\Request as Request;
use \trice\Response as Response;

/**
 * HTML Document Command. 
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class HtmlDocumentCommand extends \trice\Command {
  
  /**
   * @see \trice\Command::run()
   */
  public function run(Request $request, Response $response) {
    $response->setHeader('Content-Type', 'text/html; charset=utf-8');
    $response->setNamespace('', 'http://www.w3.org/1999/xhtml');
    $response->setMeta('robots', 'index, follow');
    $response->setLink('shortcut icon', $response->faviconHref());
  }
  
  
}
