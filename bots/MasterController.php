<?php

namespace trice\bots;

use \trice\Trice as Trice;
use \phweb\utils\DateTimeUtils as DateTimeUtils;
use \graphdock\GraphDock as GraphDock;

/**
 * Bot master controller.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class MasterController extends \trice\Controller {
	
	/**
	 * Calls a sub-method, based on the path.
	 * 
	 * @see \trice\Controller::run()
	 */
	public function run() {
		$response->set('pageTitle', 'Master bot');
		// "/bots/master/xxx" => call-level == 2
		$this->handleCall(2, 'handleNotImplementedCall');
	}

	/**
	 * Starts the master bot if it isn't running yet.
	 * 
	 * @param int $level
	 */
	public function handleStartCall($level) {
		$response = Trice::getResponse();
		$response->setStatus(200);
		$response->setHeader('Content-Type', 'text/plain; charset=utf-8');
		$message = '';
		if ($this->isActive()) {
			$message = 'Master bot is already running.';
		}
		else {
			$this->activate();
			$message = 'Activated the master bot.';
		}
		$response->set('page', $message);
		$response->set('isComplete', true);
		Trice::log($message, 'bots');
	}
	
	protected function isActive() {
		$db = Trice::getRegistryInstance('graphdock\Graphdock', 'bots', 'bots');
		$ping = $db->get('bot:master/lastPing', 0);
		return ($ping && (DateTimeUtils::getDuration($ping) < 120));
	}	
	
	protected function activate() {
		$db = Trice::getRegistryInstance('graphdock\Graphdock', 'bots', 'bots');
		$db->set('bot:master/lastPing', DateTimeUtils::getUtcUts());
		return;
		$url = $request->get('absBase') . 'bots/master';
		Trice::log("Calling {$url}", 'bots');
		$stream = new Stream($url);
		$stream->ping();
	}	
	
}
