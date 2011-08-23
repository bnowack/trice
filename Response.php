<?php

namespace trice;

use \trice\Trice as Trice;
use \trice\Configuration as Configuration;

/**
 * Trice response object.
 * 
 * Result hierarchy:
 * result
 *  - page
 *    - head
 *      - meta
 *      - links
 *      - styles
 *      - scripts
 *    - body
 *      - layout
 *        - logo
 *        - sysnav
 *        - mainnav
 *        - content
 *        - sidebar
 *        - footer
 *    
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class Response {
  
  // class variables
  protected $statusCode = 501;
  protected $statusCodes = array(
    200 => 'OK',
    301 => 'Moved Permanently',
    302 => 'Found',
    303 => 'See Other',
    403 => 'Forbidden',
    404 => 'Not Found',
    501 => 'Not Implemented',
    503 => 'Service Unavailable',
  );
  protected $headers = array();
  protected $variables = array();
  protected $templates = array();
  protected $namespaces = array();
  protected $meta = array();
  protected $links = array();
  protected $stylesheets = array();
  protected $scripts = array();

  public function __construct() {
    $this->setVariable('isComplete', false);
  }
  
  /**
   * Sets the response status.
   */
  public function setStatus($code) {
    $this->statusCode = $code;
    if (!isset($this->statusCodes[$code])) {
      $this->statusCodes[$code] = $code;
    }
    return $this;
  }
  
  /**
   * Sets a response header.
   */
  public function setHeader($name, $value) {
    $this->headers[$name] = $value;
    return $this;
  }
  
  /**
   * Returns the template for the given $container.
   */
  public function getHeader($name, $default = null) {
    return isset($this->headers[$name]) ? $this->headers[$name] : $default;
  }
  
  /**
   * Sets the template for the given $container.
   */
  public function setTemplate($container, $path) {
    $this->templates[$container] = $path;
    return $this;
  }
  
  /**
   * Returns the template for the given $container.
   */
  public function getTemplate($container, $default = null) {
    return isset($this->templates[$container]) ? $this->templates[$container] : $default;
  }
  
  /**
   * Sets a namespace prefix and URI.
   */
  public function setNamespace($prefix, $uri) {
    $this->namespaces[$prefix] = $uri;
    return $this;
  }
  
  /**
   * Returns the namespace URI for the given $prefix.
   */
  public function getNamespace($prefix, $default = null) {
    return isset($this->namespaces[$prefix]) ? $this->namespaces[$prefix] : $default;
  }
  
  /**
   * Returns the complete namespace array.
   */
  public function getNamespaces() {
    return $this->namespaces;
  }
  
  /**
   * Sets a meta element.
   */
  public function setMeta($name, $content) {
    $this->meta[$name] = $content;
    return $this;
  }
  
  /**
   * Returns the meta content for the given $name.
   */
  public function getMeta($name, $default = null) {
    return isset($this->meta[$name]) ? $this->meta[$name] : $default;
  }
  
  /**
   * Returns the complete meta array.
   */
  public function getMetaElements() {
    return $this->meta;
  }
  
  /**
   * Sets a link relation.
   */
  public function setLink($relation, $href) {
    $this->links[$relation] = $href;
    return $this;
  }
  
  /**
   * Returns the link href for the given $relation.
   */
  public function getLink($relation, $default = null) {
    return isset($this->links[$relation]) ? $this->links[$relation] : $default;
  }
  
  /**
   * Returns the complete links array.
   */
  public function getLinks() {
    return $this->links;
  }
  
  /**
   * Adds a stylesheet.
   */
  public function addStylesheet($path, $media = 'all') {
    if (!isset($this->stylesheets[$media])) {
      $this->stylesheets[$media] = array();
    }
    if (!in_array($path, $this->stylesheets[$media])) {
      $this->stylesheets[$media][] = $path;
    }
    return $this;
  }
  
  /**
   * Returns the stylesheets for a given $media type, or the complete set.
   */
  public function getStylesheets($media = null, $default = null) {
    if ($media !== null) {
      return isset($this->stylesheets[$media]) ? $this->stylesheets[$media] : $default;
    }
    return $this->stylesheets;
  }
  
  /**
   * Adds a script.
   */
  public function addScript($src, $type = 'text/javascript') {
    if (!isset($this->scripts[$type])) {
      $this->scripts[$type] = array();
    }
    if (!in_array($src, $this->scripts[$type])) {
      $this->scripts[$type][] = $src;
    }
    return $this;
  }
  
  /**
   * Returns the scripts for a given $type, or the complete set.
   */
  public function getScripts($type = 'text/javascript', $default = null) {
    if ($type !== null) {
      return isset($this->scripts[$type]) ? $this->scripts[$type] : $default;
    }
    return $this->scripts;
  }
  
  /**
   * Returns the complete stylesheets array.
   */
  public function getAllStylesheets() {
    return $this->stylesheets;
  }
  
  /**
   * Sets a response variable.
   */
  public function setVariable($name, $value) {
    $this->variables[$name] = $value;
    return $this;
  }
  
  /**
   * Alias for setVariable
   */
  public function set($name, $value) {
    return $this->setVariable($name, $value);
  }
  
  /**
   * Adds a response variable.
   */
  public function addVariable($name, $value) {
    if (!isset($this->variables[$name])) {
      $this->variables[$name] = array();
    }
    $this->variables[$name][] = $value;
    return $this;
  }
  
  /**
   * Alias for addVariable
   */
  public function add($name, $value) {
    return $this->addVariable($name, $value);
  }
  
  /**
   * Returns a response variable.
   */
  public function getVariable($name, $default = null) {
    return isset($this->variables[$name]) ? $this->variables[$name] : $default;
  }
  
  /**
   * Alias for getVariable.
   */
  public function get($name, $default = null) {
    return $this->getVariable($name, $default);
  }
  
  /**
   * Returns the complete variables array.
   */
  public function getVariables() {
    return $this->variables;
  }
  
  
  /**
   * Builds the response result (if not already done) by processing the page template.
   * 
   * @param Request $request 
   */
  public function buildResult(Request $request) {
    /* hard-wired response, nothing to do */
    if ($this->getVariable('result') !== null) {
      return $this;
    }
    /* Use a page template. */
    $this->setVariable('result', $this->render('page'));
    return $this;
  }
  
  /**
   * Sends the defined headers to the client.
   * 
   * @param Request $request 
   */
  public function sendHeaders(Request $request) {
    /* status header */
    header("HTTP/1.1 {$this->statusCode} {$this->statusCodes[$this->statusCode]}");
    /* response headers */
    foreach ($this->headers as $name => $value) {
      header("{$name}: {$value}");
    }
    return $this;
  }
  
  /**
   * Sends 
   * 
   * @param Request $request 
   */
  public function sendResult(Request $request) {
    echo $this->getVariable('result');
  }
  
  /**
   * Includes and renders the template or content associated with the given container.
   * 
   * Examples:
   *  render the head section using the layout's or system's "head.tpl" template:
   *    $this->render('head');
   * 
   *  render the head section using a custom template:
   *    $this->setTemplate('head', 'path/to/my/head.tpl');
   *    $this->render('head');
   *  
   *  render the head section using custom code:
   *    $this->set('head', 'my head data');
   *    $this->render('head');
   * 
   *  disable head section output:
   *    $this->set('head', '');
   * 
   * @param string $container
   */
  public function render($container) {
    $result = $this->getVariable($container);
    if (is_array($result)) {
      $result = print_r($result, 1);
    }
    /* no result set yet, try the template associated with the container */
    if ($result === null) {
      $layout = Configuration::get('app/layout', 'system');
      $templates = array(
        /* custom template */
        $this->getTemplate($container),
        /* app layout template */
        "layouts/{$layout}/{$container}.tpl",
        /* system layout template */
        "layouts/system/{$container}.tpl"
      );
      foreach ($templates as $template) {
        if ($template && file_exists($template)) {
          $renderStart = microtime(true);
          ob_start(); 
          ob_implicit_flush(0);
          include($template);
          $result = ob_get_clean();
        }
      }
    }
    /* localisation */
    $lang = $this->getVariable("{$container}Language");
    if ($lang) {
      $result = $this->translate($result, $lang);
    }
    /* encoding (e.g. utf8) */
    $enc = $this->getVariable("{$container}Encoding");
    if ($enc) {
      $result = $this->encode($result, $enc);
    }
    return $result;
  }
  
  public function translate($result, $language) {
    return $result;
  }
  
  public function encode($result, $encoding) {
    return $result;
  }
  
  /**
   * Catches undefined method calls in the template.
   */
  public function __call($methodName, $args) {
    $className = ucfirst($methodName) . 'Helper';
    $namespaces = Configuration::get('trice/helper_namespaces', array());
    foreach ($namespaces as $ns) {
      $fullClassName = rtrim($ns, '\\') . '\\' . $className;
      if (class_exists($fullClassName, true)) {
        $helper = Trice::getRegistryInstance($fullClassName);
        return call_user_func_array(array($helper, 'run'), $args);
      }
    }
    return "[not implemented: {$className}()]";
  }
  
  public function __get($propertyName) {
    $result = $this->getVariable($propertyName);
    if ($result === null) {
      $request = Trice::getRequest();
      $result = $request->get($propertyName);
    }
    return $result;
  }
  
}
