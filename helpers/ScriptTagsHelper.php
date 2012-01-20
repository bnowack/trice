<?php

namespace trice\helpers;

use \trice\Trice as Trice;

/**
 * Script-Tags Helper.
 * Generates <style /> markup.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class ScriptTagsHelper {
	
	/**
	 * Returns script-tags.
	 * 
	 * @see \trice\Helper::run()	 * 
	 */
	public function run() {
		$r = '';
		$request = Trice::getRequest();
		$response = Trice::getResponse();
		$layout = Trice::getConfiguration('app/layout', 'system');
		$relBase = $request->get('relBase', 'computed');
		$dirs = array(
			"layouts/{$layout}/",
			"layouts/system/",
			"code/"
		);
		
		$els = $response->getScripts(null);
	
		foreach ($els as $type => $paths) {
			foreach ($paths as $path) {
				$src = $path;
				$cacheId = date('dHi');
				// use the first matching path
				foreach ($dirs as $dir) {
					if ($dir && file_exists("{$dir}{$path}")) {
						$src = "{$relBase}{$dir}{$path}";
						$cacheId = date('dHi', filemtime("{$dir}{$path}"));
						break;
					}
				}
				$r .= "\n		<script type=\"{$type}\" src=\"{$src}?{$cacheId}\"></script>";
			}
		}
		return $r;
	}
	
}
