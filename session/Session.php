<?php

namespace trice\session;

use \trice\Trice as Trice;
use \phweb\utils\StringUtils as StringUtils;
use \phweb\utils\FileUtils as FileUtils;
use \phweb\utils\DateTimeUtils as DateTimeUtils;

/**
 * Session.
 * 
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class Session {
	
	protected $sid;
	protected $cookieName;
	protected $cookieBase;
	protected $data;
	protected $changed;
	
	public function __construct($sid = null) {
		$request = Trice::getRequest();
		$this->cookieName = substr(md5($request->get('absBase', 'computed')), -16);
		$this->cookieBase = $request->get('relBase', 'computed');
		$this->fileBase = 'data/sessions/';
		$this->sid = $sid ? $sid : $this->getId();
		$this->data = $this->loadData();
		$this->changed = false;
	}
	
	protected function loadData() {
		if (!$this->sid) return array();
		$filePath = $this->fileBase . $this->sid . '.json';
		if (!file_exists($filePath)) return array();
		return json_decode(file_get_contents($filePath), true);
	}
	
	public function persist($force = false) {
		if (!$this->sid) return;
		if (!$force && !$this->changed) return;
		$filePath = $this->fileBase . $this->sid . '.json';
		$data = json_encode($this->data);
		FileUtils::saveFile($filePath, $data);
	}
	
	public function set($name, $value) {
		$this->data[$name] = $value;
		$this->changed = true;
		return $this;
	}
	
	public function get($name) {
		return isset($this->data[$name]) ? $this->data[$name] : null;
	}
	
	public function getId() {
		return Trice::getRequest()->get($this->cookieName, 'cookie');
	}
	
	public function signedIn() {
		return $this->get('account');
	}
	
	public function create() {
		// generate id
		$this->sid = StringUtils::rand(16, 's');
		$this->setCookie()
			->set('created', DateTimeUtils::getUtcXsd(null, true))
			->set('modified', DateTimeUtils::getUtcXsd(null, true))
			/* clean up */
			->removeExpiredSessions();
		return $this;
	}
	
	public function destroy() {
		if (!$this->sid) return;
		$filePath = $this->fileBase . $this->sid . '.json';
		FileUtils::removeFile($filePath);
	}
	
	protected function removeExpiredSessions() {
		return $this;
	}
	
	protected function setCookie() {
		$id = $this->sid;
		$cn = $this->cookieName;
		$cb = $this->cookieBase;
		$exp = time() + (3600 * 24 * 30); // 30 days
		setCookie($cn, $id, $exp , $cb);
		return $this;
	}
	
	protected function removeCookie() {
		$id = $this->sid;
		$cn = $this->cookieName;
		$cb = $this->cookieBase;
		$exp = time() -1000; // past
		setCookie($cn, $id, $exp , $cb);
		unset($_COOKIE[$cn]);
		return $this;
	}
	
	public function getToken() {
		return md5($this->getId());
	}
	
	
}
