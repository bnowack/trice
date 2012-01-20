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
class Field {
	
	protected $id;
	protected $definition;
	
	public function __construct($id, $def) {
		$this->id = $id;
		$this->definition = $def;
		$this->setDefaults();
	}
	
	protected function setDefaults() {
		
	}
	
	protected function getFocusFieldId() {
		return "{$this->definition['formId']}-{$this->id}-focus";
	}
	
	/**
	 * 
	 */
	public function getHtml() {
		return '
					<div' . StringUtils::htmlClass($this->definition['classes']) . '>
						' . $this->getLabelHtml() . '
						' . $this->getEntriesHtml() . '
						' . $this->getInfoHtml() . '
					</div>
		';
	}
	
	protected function getLabelHtml() {
		return '
						<label for="' . $this->getFocusFieldId() . '-focus">' . $this->definition['label'] . '</label>
		';
	}
	
	protected function getEntriesHtml() {
		$r = '';
		$entries = $this->definition['entries'];
		foreach ($entries as $pos => $entry) {
			$r .= $this->getEntryContainerHtml($entry, $pos);
		}
		return '
						<div class="entries">
							' . $r . '
						</div>
		';
	}
	
	protected function getEntryContainerHtml($entry, $pos) {
		$def = $this->definition;
		$classes = array('entry', "pos-$pos");
		if ($def['error'] && $def['errorPos'] == $pos) {
			$classes[] = 'error';
		}
		return '
							<div' . StringUtils::htmlClass($classes) . '>
								' . $this->getEntryHtml($entry, $pos) . '
							</div>
		';
	}
	
	protected function getEntryHtml($entry, $pos) {
		return '';
	}

	protected function getInfoHtml() {
		if (!empty($this->definition['info'])) {
			return '
						<div class="info">' . $this->definition['info'] . '</div>
			';
		}
	}
	
	public function validate() {
		$entries = $this->definition['entries'];
		foreach ($entries as $pos => $entry) {
			$error = $this->validateEntry($entry);
			if ($error) return array($error, $pos);
		}
		return array(false, false);
	}
	
	protected function validateEntry($entry) {
		$def = $this->definition;
		// regex specified
		if (is_string($entry) && !empty($def['match'])) {
			if (!preg_match($def['match'], $entry)) {
				return empty($def['matchError']) ? 'Invalid value' : $def['matchError'];
			}
		}
		return false;
	}
	
	
}
