<?php

namespace trice\form;

use \trice\Trice as Trice;

/**
 * Form field.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class TextField extends Field {
	
	protected $fieldType = 'text';
	
	protected function getEntryHtml($entry, $pos) {
		$idCode = ($pos == 0) ? ' id="' . $this->getFocusFieldId() . '"' : '';
		$name = "{$this->id}[$pos]";
		$value = print_r($entry, 1);// should be a plain literal already, though
		return '
			<input' . $idCode. ' name="' . $name . '" type="' . $this->fieldType . '" value="' . $value . '" autocomplete="off" />
		';
	}
	
	
}
