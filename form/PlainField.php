<?php

namespace trice\form;

use \trice\Trice as Trice;

/**
 * Form field.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class PlainField extends Field {
	
	protected $fieldType = 'plain';
	
	public function getHtml() {
		return $this->getEntriesHtml();
	}
	
	protected function getEntriesHtml() {
		$entries = $this->definition['entries'];
		return $entries[0];
	}
	
}
