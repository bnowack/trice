<?php

namespace trice;

use \trice\Trice as Trice;
use \phweb\Configuration as Configuration;
use \phweb\utils\StringUtils as StringUtils;

/**
 * Trice response object.
 * 
 * Result hierarchy:
 * result
 *	- page
 *	- head
 *		- meta
 *		- links
 *		- styles
 *		- scripts
 *	- body
 *		- layout
 *		- logo
 *		- sysnav
 *		- mainnav
 *		- content
 *		- sidebar
 *		- footer
 *	
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class Response {
	
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
	protected $scriptCalls = array();

	public function __construct() {
		$this->setVariable('isComplete', false);
	}
	
	/**
	* Sets the response status.
	* 
	* @param string $code
	* @return Response
	*/
	public function setStatus($code) {
		$this->statusCode = $code;
		if (!isset($this->statusCodes[$code])) {
			$this->statusCodes[$code] = $code;
		}
		return $this;
	}
	
	public function getStatusString() {
		return "{$this->statusCode} {$this->statusCodes[$this->statusCode]}";
	}
	
	/**
	* Sets a response header.
	* 
	* @param string $name
	* @param string $value
	* @return Response
	*/
	public function setHeader($name, $value) {
		$this->headers[$name] = $value;
		return $this;
	}
	
	/**
	* Returns the template for the given $container.
	* 
	* @param string $name
	* @param mixed $default
	* @return mixed
	*/
	public function getHeader($name, $default = null) {
		return isset($this->headers[$name]) ? $this->headers[$name] : $default;
	}
	
	/**
	* Sets the template for the given $container.
	* 
	* @param string $container
	* @param string $path
	* @return Response
	*/
	public function setTemplate($container, $path) {
		$this->templates[$container] = $path;
		return $this;
	}
	
	/**
	* Returns the template for the given $container.
	* 
	* @param string $container
	* @param mixed $default
	* @return mixed
	*/
	public function getTemplate($container, $default = null) {
		return isset($this->templates[$container]) ? $this->templates[$container] : $default;
	}
	
	/**
	* Sets a namespace prefix and URI.
	* 
	* @param string $prefix
	* @param string $uri
	* @return Response
	*/
	public function setNamespace($prefix, $uri) {
		$this->namespaces[$prefix] = $uri;
		return $this;
	}
	
	/**
	* Returns the namespace URI for the given $prefix.
	* 
	* @param string $prefix
	* @param mixed $default
	* @return mixed
	*/
	public function getNamespace($prefix, $default = null) {
		return isset($this->namespaces[$prefix]) ? $this->namespaces[$prefix] : $default;
	}
	
	/**
	* Returns the complete namespace array.
	* 
	* @return array
	*/
	public function getNamespaces() {
		return $this->namespaces;
	}
	
	/**
	* Sets a meta element.
	* 
	* @param string $name
	* @param string $content
	* @return Response
	*/
	public function setMeta($name, $content) {
		$this->meta[$name] = $content;
		return $this;
	}
	
	/**
	* Returns the meta content for the given $name.
	* 
	* @param string $name
	* @param mixed $default
	* @return mixed
	*/
	public function getMeta($name, $default = null) {
		return isset($this->meta[$name]) ? $this->meta[$name] : $default;
	}
	
	/**
	* Returns the complete meta array.
	* 
	* @return array
	*/
	public function getMetaElements() {
		return $this->meta;
	}
	
	/**
	* Sets a link relation.
	* 
	* @param string $relation
	* @param string $href
	* @return Response
	*/
	public function setLink($relation, $href) {
		$this->links[$relation] = $href;
		return $this;
	}
	
	/**
	* Returns the link href for the given $relation.
	* 
	* @param string $relation
	* @param mixed $default
	* @return mixed
	*/
	public function getLink($relation, $default = null) {
		return isset($this->links[$relation]) ? $this->links[$relation] : $default;
	}
	
	/**
	* Returns the complete links array.
	* 
	* @return array
	*/
	public function getLinks() {
		return $this->links;
	}
	
	/**
	* Adds a stylesheet.
	* 
	* @param string $path
	* @param string $media
	* @return Response
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
	* 
	* @param string $media
	* @param mixed $default
	* @return mixed
	*/
	public function getStylesheets($media = null, $default = array()) {
		if ($media !== null) {
			return isset($this->stylesheets[$media]) ? $this->stylesheets[$media] : $default;
		}
		return $this->stylesheets;
	}
	
	/**
	* Adds a script.
	* 
	* @param string $src
	* @param string $type
	* @return Response
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
	* 
	* @param string $type
	* @param mixed $default
	* @return mixed
	*/
	public function getScripts($type = 'text/javascript', $default = array()) {
		if ($type !== null) {
			return isset($this->scripts[$type]) ? $this->scripts[$type] : $default;
		}
		return $this->scripts;
	}
	
	public function addScriptCall($call, $container = 'body') {
		if (!isset($this->scriptCalls[$container])) {
			$this->scriptCalls[$container] = array();
		}
		if (!in_array($call, $this->scriptCalls[$container])) {
			$this->scriptCalls[$container][] = $call;
		}
		return $this;
	}
	
	public function getScriptCalls($container) {
		return isset($this->scriptCalls[$container]) ? $this->scriptCalls[$container] : array();
	}

	/**
	* Sets a response variable.
	* 
	* @param string $name
	* @param string $value
	* @return Response
	*/
	public function setVariable($name, $value) {
		$this->variables[$name] = $value;
		return $this;
	}

	/**
	* Alias for setVariable
	* 
	* @see self::setVariable()
	*/
	public function set($name, $value) {
		return $this->setVariable($name, $value);
	}

	/**
	* Adds a response variable.
	* 
	* @param string $name
	* @param string $value
	* @return Response
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
	* 
	* @see self::addVariable
	*/
	public function add($name, $value) {
		return $this->addVariable($name, $value);
	}
	
	/**
	* Appends data or an entry to a response variable.
	* 
	* @param string $name
	* @param string $value
	* @return Response
	*/
	public function append($name, $value) {
		if (!isset($this->variables[$name])) {
			$this->setVariable($name, $value);
		}
		elseif (is_string($this->variables[$name])) {
			$this->variables[$name] .= $value;
		}
		elseif (is_array($this->variables[$name])) {
			$this->variables[$name][] = $value;
		}
		return $this;
	}
	
	/**
	* Returns a response variable.
	* 
	* @param string $name
	* @param mixed $default
	* @return mixed 
	*/
	public function getVariable($name, $default = null) {
		return isset($this->variables[$name]) ? $this->variables[$name] : $default;
	}
	
	/**
	* Alias for getVariable.
	* 
	* @see self::getVariable()
	*/
	public function get($name, $default = null) {
		return $this->getVariable($name, $default);
	}
	
	/**
	* Returns the complete variables array.
	* 
	* @return array
	*/
	public function getVariables() {
		return $this->variables;
	}
	
	public function complete($state = true) {
		return $this->set('isComplete', $state);
	}
	
	/**
	* Builds the response result (if not already done) by processing the page template.
	* 
	* @param Request $request
	* @return Response
	*/
	public function buildResult(\phweb\Request $request) {
		// hard-wired response, nothing to do
		if ($this->getVariable('result') !== null) {
			return $this;
		}
		// Use a page template.
		$this->setVariable('result', $this->render('page'));
		return $this;
	}
	
	/**
	* Sends the defined headers to the client.
	* 
	* @param Request $request 
	* @return Response
	*/
	public function sendHeaders(\phweb\Request $request) {
		if (!headers_sent()) {
			// status header
			header("HTTP/1.1 {$this->statusCode} {$this->statusCodes[$this->statusCode]}");
			// response headers
			foreach ($this->headers as $name => $value) {
				header("{$name}: {$value}");
			}
		}
		return $this;
	}
	
	/**
	* Prints the response result.
	* 
	* @param Request $request 
	*/
	public function sendResult(\phweb\Request $request) {
		echo $this->getVariable('result');
		return $this;
	}
	
	/**
	* Includes and renders the template or content associated with the given container.
	* 
	* Examples:
	*	render the head section using the layout's or system's "head.tpl" template:
	*	$this->render('head');
	* 
	*	render the head section using a custom template:
	*	$this->setTemplate('head', 'path/to/my/head.tpl');
	*	$this->render('head');
	*	
	*	render the head section using custom code:
	*	$this->set('head', 'my head data');
	*	$this->render('head');
	* 
	*	disable head section output:
	*	$this->set('head', '');
	* 
	* @param string $container
	* @return string
	*/
	public function render($container) {
		$result = $this->getVariable($container);
		if (is_array($result)) {
			$result = print_r($result, 1);
		}
		// no result set yet, try the template associated with the container
		if ($result === null) {
			$layout = Configuration::get('app/layout', 'system');
			$templates = array(
				// custom template
				$this->getTemplate($container),
				// app layout template
				"layouts/{$layout}/{$container}.tpl",
				// system layout template
				"layouts/system/{$container}.tpl"
			);
			foreach ($templates as $template) {
				if ($template && file_exists($template)) {
					$renderStart = microtime(true);
					ob_start(); 
					ob_implicit_flush(0);
					include($template);
					$result = ob_get_clean();
					break;
				}
			}
		}
		// script calls
		$calls = $this->getScriptCalls($container);
		if ($calls) {
			$result .= '
				<script type="text/javascript">
					try {' . implode(";\n\t\t", $calls) . '} catch(e) { if (console) console.log(e) }
				</script>
			';
		}
		// localisation
		$lang = $this->getVariable("{$container}Language");
		if ($lang) {
			$result = $this->translate($result, $lang);
		}
		// encoding (e.g. utf-8)
		$enc = $this->getVariable("{$container}Encoding");
		if ($enc) {
			$result = $this->encode($result, $enc);
		}
		return $result;
	}
	
	/**
	* Translates a string value.
	* 
	* @param string $value
	* @param string $language
	* @return string
	* @todo implement it
	*/
	public function translate($value, $language) {
		return $value;
	}
	
	/**
	* Encodes a string value (e.g. as "utf-8").
	* 
	* @param string $value
	* @param string $encoding
	* @return string
	* @todo implement it
	*/
	public function encode($value, $encoding) {
		return StringUtils::toUtf8($value);
	}
	
	/**
	* Interceptor to catch undefined method calls (mainly in templates).
	* 
	* @param string $methodName
	* @param array $args
	* @return mixed
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
	
	/**
	* Interceptor to catch undefined property accesses (mainly in templates).
	* 
	* @param string $propertyName
	* @return mixed 
	*/
	public function __get($propertyName) {
		$result = $this->getVariable($propertyName);
		if ($result === null) {
			$result = Trice::getRequest()->get($propertyName);
		}
		return $result;
	}
	
	public function notImplemented() {
		return $this->setStatus(501)
			->set('pageTitle', $this->getStatusString())
			->set('content', $this->getStatusString())
			->set('isComplete', true);
	}	
	
	public function notFound() {
		return $this->setStatus(404)
			->set('pageTitle', $this->getStatusString())
			->set('content', $this->getStatusString())
			->set('isComplete', true);
	}	
	
	
}
