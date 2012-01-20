<?php

namespace trice\form;

use \trice\Trice as Trice;
use \phweb\utils\StringUtils as StringUtils;

/**
 * Form field.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class SelectField extends Field {
	
	protected $fieldType = 'select';
	
	protected function setDefaults() {
		$defaults = array(
			'size' => 1,
			'options' => array(),
		);
		foreach ($defaults as $k => $v) {
			if (!isset($this->definition[$k])) $this->definition[$k] = $v;
		}
	}

	
	protected function getEntryHtml($entry, $pos) {
		$idCode = ($pos == 0) ? ' id="' . $this->getFocusFieldId() . '"' : '';
		$name = "{$this->id}[$pos]";
		return '
			<select' . $idCode. ' name="' . $name . '" size="' . $this->definition['size'] . '">
				' . $this->getOptionsHtml($entry, $pos) . '
			</select>
		';
		
	}
	
	protected function getOptionsHtml($entry, $pos) {
		$r = '';
		foreach ($this->definition['options'] as $value => $label) {
			$classes = array();
			$selCode = '';
			if ($value == $entry) {
				$classes[] = 'selected';
				$selCode = ' selected="selected"';
			}
			$r .= '<option' . StringUtils::htmlClass($classes) . $selCode . ' value="' . $value . '">' . $label . '</option>';
		}
		return $r;
	}
	
	
}
