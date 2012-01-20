<?php

namespace trice\helpers;

use \trice\Trice as Trice;
use \phweb\Configuration as Configuration;

/**
 * Style-Tags Helper.
 * Generates <style /> markup.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class StyleTagsHelper {

	/**
	 * Returns style-tags.
	 * 
	 * @see \trice\Helper::run()	 * 
	 */
	public function run() {
		$r = '';
		$request = Trice::getRequest();
		$response = Trice::getResponse();
		$layout = Configuration::get('app/layout', 'system');
		$relBase = $request->get('relBase', 'computed');
		$dirs = array(
			"layouts/{$layout}/",
			"layouts/system/",
			"code/"
		);
		
		$els = $response->getStylesheets();
	
		foreach ($els as $media => $paths) {
			$r .= "\n		<style type=\"text/css\" media=\"{$media}\">";
			foreach ($paths as $path) {
				$url = $path;
				$cacheId = date('dHi');
				// use the first matching path
				foreach ($dirs as $dir) {
					if ($dir && file_exists("{$dir}{$path}")) {
						$url = "{$relBase}{$dir}{$path}";
						$cacheId = date('dHi', filemtime("{$dir}{$path}"));
						break;
					}
				}
				$r .= "\n			@import url({$response->html("$url?{$cacheId}")});";
			}
			$r .= "\n		</style>";
		}
		return $r;
	}

}
