<?php

namespace trice\form;

use \trice\Trice as Trice;

/**
 * Form field.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class HiddenField extends TextField {
	
	protected $fieldType = 'hidden';
	
	protected function getLabelHtml() {
	}
	
	protected function getInfoHtml() {
	}
	
	
}
