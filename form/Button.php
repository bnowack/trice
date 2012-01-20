<?php

namespace trice\form;

use \trice\Trice as Trice;
use \phweb\utils\StringUtils as StringUtils;

/**
 * Form button.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class Button {
	
	protected $id;
	protected $definition;
	
	public function __construct($id, $def) {
		$this->id = $id;
		$this->definition = $def;
	}
		
	/**
	 * 
	 */
	public function getHtml() {
		$classes = array_merge(array('button', $this->definition['type'], $this->id), $this->definition['classes']);
		return '
					<input' . StringUtils::htmlClass($classes) . ' type="' . $this->type . '" name="' . $this->id . '" value="' . $this->definition['label'] . '" />
		';
	}
	
}
