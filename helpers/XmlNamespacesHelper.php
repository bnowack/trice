<?php

namespace trice\helpers;

use \trice\Trice as Trice;

/**
 * XML Namespaces Helper.
 * Generates xmlns markup.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class XmlNamespacesHelper {
	
	/**
	 * Returns xmlns:prefix= or xmlns= markup from defined namespaces
	 * 
	 * @see \trice\Helper::run()	 * 
	 */
	public function run() {
		$r = '';
		$namespaces = Trice::getResponse()->getNamespaces();
		foreach ($namespaces as $prefix => $uri) {
			$prefix = $prefix ? 'xmlns:' . $prefix : 'xmlns';
			$r .= ($r ? ' ' : '') . $prefix . '="' . $uri . '"';
		}
		return $r ? " $r" : '';
	}
	
}
