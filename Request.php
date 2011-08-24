<?php

namespace trice;

/**
 * Trice request object.
 * 
 * Logs used: debug
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class Request {
  
  protected $parameters;

  public function __construct() {
    $this->buildParameters();
  }
  
  /**
   * Initialises the request parameters
   */
  protected function buildParameters() {
    $this->parameters = array(
      'get' => $this->buildGetParameters(),
      'post' => $this->buildPostParameters(),
      'files' => $this->buildFileParameters(),
      'headers' => $this->buildHeaderParameters(),
      'computed' => $this->buildComputedParameters(),
      'app' => array()
    );
  }
  
  /**
   * Returns the request's GET parameters
   * @return array
   */
  protected function buildGetParameters() {
    return isset($_GET) ? $_GET : array();
  }
  
  /**
   * Returns the request's POST parameters
   * @return array
   */
  protected function buildPostParameters() {
    return isset($_POST) ? $_POST : array();
  }
  
  /**
   * Returns the request's FILES parameters
   * @return array
   */
  protected function buildFileParameters() {
    return isset($_FILES) ? $_FILES : array();
  }
  
  /**
   * Returns the request's header parameters
   * @return array
   */
  protected function buildHeaderParameters() {
    return isset($_SERVER) ? $_SERVER : array();
  }
  
  /**
   * Returns the request's path parameters.
   */
  protected function buildComputedParameters() {
    /* e.g. http://example.com:8000/myapp/users/15.json?foo=bar */
    $r = array(
      'port' => null,           // 8000
      'protocol' => null,       // http
      'host' => null,           // example.com
      'server_url' => null,     // http://example.com:8000/
      'query_string' => null,   // foo=bar
      'rel_base' => null,       // /myapp/
      'abs_base' => null,       // http://example.com:8000/myapp/
      'path' => null,           // users/15.json?foo=bar
      'clean_path' => null,     // users/15.json
      'extension' => null,      // json
      'resource_path' => null,  // users/15
      'resource_base' => null,  // http://example.com:8000/myapp/ || config: [app/resource_base]
      'resource_id' => null,    // [resource_base]users/15
      'full_url' => null,       // http://example.com:8000/myapp/users/15.json?foo=bar
      'clean_url' => null,      // http://example.com:8000/myapp/users/15.json
    );
    if (isset($_SERVER)) {
      /* port */
      $r['port'] = $_SERVER['SERVER_PORT'];
      /* protocol */
      $r['protocol'] = preg_replace('/^([a-z0-9]+).*/', '\\1', strtolower($_SERVER['SERVER_PROTOCOL']));
      if (($r['protocol'] == 'http') && ($r['port'] == 443)) {
        $r['protocol'] = 'https';
      }
      /* host, server */
      $r['host'] = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
      $r['server_url'] = $r['protocol'] . '://' . $r['host'] . ($r['port'] != 80 ? ':' . $r['port'] : '') . '/';
      /* query string */
      $r['query_string'] = $_SERVER['QUERY_STRING'];
      /* base */
      $r['rel_base'] = preg_replace('/index\.php$/', '', $_SERVER['SCRIPT_NAME']);
      $r['abs_base'] = rtrim($r['server_url'], '/') . $r['rel_base'];
      /* relevant app path */
      $r['path'] = preg_replace('/^' . preg_quote($r['rel_base'], '/') . '/', '', $_SERVER['REQUEST_URI']);
      $r['clean_path'] = preg_replace('/\?.*$/', '', $r['path']);
      $r['path_parts'] = explode('/', $r['clean_path']);
      /* REST / Linked Data */
      $r['extension'] = preg_match('/^[^\.]*\.(.+)$/', $r['clean_path'], $m) ? strtolower($m[1]) : null;
      $r['resource_path'] = preg_replace('/\.' . preg_quote($r['extension'], '/') . '$/i', '', $r['clean_path']);
      $r['resource_base'] = Configuration::get('app/uri_base', $r['abs_base']);
      $r['resource_id'] = $r['resource_base'] . $r['resource_path'];
      $r['full_url'] = $r['abs_base'] . $r['path'];
      $r['clean_url'] = $r['abs_base'] . $r['clean_path'];
    }
    return $r;
  }
  
  /**
   * Returns a request parameter value or NULL.
   * 
   * @param string $name
   * @param string $category
   * @return mixed
   */
  public function getParameter($name, $category = '') {
    $categories = $category ? array($category) : array_keys($this->parameters);
    foreach ($categories as $category) {
      if (isset($this->parameters[$category])) {
        if (isset($this->parameters[$category][$name])) {
          return $this->parameters[$category][$name];
        }
      }
    }
    return null;
  }
  
  /**
   * Alias for getParameter.
   * 
   * @see getParameter()
   */
  public function get($name, $category = '') {
    return $this->getParameter($name, $category);
  }
  
  /**
   * Overwrites a request parameter.
   */
  public function setParameter($category, $name, $value) {
    if (!isset($this->parameters[$category])) {
      $this->parameters[$category] = array();
    }
    $this->parameters[$category][$name] = $value;
    return $this;
  }

  /**
   * Checks whether the given pattern is part of the request path.
   */
  public function pathMatches($pattern) {
    /* Create a valid regex pattern. */
    if (!preg_match('/^\//', $pattern)) {
      $pattern = preg_replace('/([\/\.])/', '\\\\\1', $pattern);
      $pattern = '/' . $pattern . '/';
    }
    /* Compare the pattern against the request path. */
    $path = $this->getParameter('clean_path', 'computed');
    $result = preg_match($pattern, $path);
    Trice::log("Request::pathMatches(): Testing '{$pattern}' against '{$path}': {$result}.", 'debug');
    return $result;
  }
  
}
