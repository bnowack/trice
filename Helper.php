<?php

namespace trice;

/**
 * Helper interface.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
interface Helper {
	
	/**
	 * Executes the helper.
	 *	
	 * @param mixed $args
	 */
	public function run($args = array());
	
}
