<?php

namespace trice\helpers;

use \trice\Trice as Trice;
use \phweb\utils\StringUtils as StringUtils;

/**
 * Nav Helper.
 * Generates navigation markup.
 * 
 * @package App
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class NavHelper {

	/**
	 * Returns style-tags.
	 * 
	 * @see \trice\Helper::run()
	 */
	public function run($items) {
		$r = '';
		if (empty($items)) $items = array();
		$relBase = Trice::getRequest()->get('relBase', 'computed');
		$isFirst = true;
		foreach ($items as $path => $label) {
			$isRoot = false;
			if (substr($path, -1) == '?') {
				$isRoot = true;
				$path = rtrim($path, '?');
			}
			$href = $relBase . $path;
			$classes = array();
			if ($this->isRequestParentPath($path, $isRoot)) {
				$classes[] = 'selected';
			}
			$r .= '<li' . StringUtils::htmlClass($classes) . '><a href="' . $href . '">' . $label . '</a></li>';
			$isFirst = false;
		}
		return $r ? '<ul class="nav">' . $r . '</ul>' : '';
	}
	
	protected function isRequestParentPath($path, $isRoot) {
		$requestPath = Trice::getRequest()->get('resourcePath', 'computed');
		if ($isRoot && $requestPath == '') return true;
		if ($path == $requestPath) return true;
		if (preg_match('/^' . preg_quote($path, '/') . '/', $requestPath)) return true;
		return false;
	}

}
