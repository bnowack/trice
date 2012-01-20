<?php

namespace trice\form;

use \trice\Trice as Trice;

/**
 * Form field.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class DateField extends Field {
	
	protected $fieldType = 'text';
	
	protected function setDefaults() {
		$defaults = array(
			'format' => 'Y-m-d',
			'minValue' => '1900-01-01' 
		);
		foreach ($defaults as $k => $v) {
			if (!isset($this->definition[$k])) $this->definition[$k] = $v;
		}
	}
	
	protected function getEntryHtml($entry, $pos) {
		$idCode = ($pos == 0) ? ' id="' . $this->getFocusFieldId() . '"' : '';
		$name = "{$this->id}[$pos]";
		$value = $entry;// should be a plain literal already, though
		
		return '
			<div class="datepicker" data-format="' . $this->definition['format'] . '" data-min-value="' . $this->definition['minValue'] . '">
				<input' . $idCode. ' name="' . $name . '" type="' . $this->fieldType . '" value="' . $value . '" />
				<a class="display"></a>
				<div class="popover"></div>
			</div>
		';
	}
	
	
}
