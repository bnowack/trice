<?php

namespace trice\form;

use \trice\Trice as Trice;
use \phweb\utils\StringUtils as StringUtils;

/**
 * Trice Form.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class Form {
	
	public $definition = array();
	protected $computedDefinition = null;
	protected $message = '';
	
	public function __construct($def) {
		$this->definition = $def;
	}
	
	public function setDefinition($def) {
		$this->definition = $def;
	}
	
	protected function getFormId() {
		return isset($this->definition['id']) ? $this->definition['id'] : 'form-' . md5(print_r($this->definition, 1));
	}
	
	public function getEvent() {
		$request = Trice::getRequest();
		// valid token
		if ($request->getFirst('form-token') != $this->getToken()) return false;
		// no error
		$def = $this->getComputedDefinition();
		if (!empty($def['errorFields'])) return false;
		// event param
		return $request->getFirst('form-event');
	}
	
	public function submitted() {
		$request = Trice::getRequest();
		return ($request->getFirst('form-token') == $this->getToken());
	}
	
	public function setMessage($msg) {
		$this->message = $msg;
	}
	
	public function addClass($class) {
		$def = $this->getComputedDefinition();
		$def['classes'][] = $class;
		$this->computedDefinition = $def;
	}
	
	protected function getComputedDefinition() {
		if (!$this->computedDefinition) {
			$def = $this->definition;
			// defaults
			$def = $this->computeFormInformation($def);
			// fields
			$def = $this->computeFields($def);
			// buttons
			$def = $this->computeButtons($def);
			$this->computedDefinition = $def;
		}
		return $this->computedDefinition;
	}
	
	public function computeDefinition() {
		$this->computedDefinition = $this->getComputedDefinition();
	}
	
	protected function computeFormInformation($def) {
		$defaults = array(
			'id' => $this->getFormId(),
			'classes' => array(),
			'action' => Trice::getRequest()->get('resourceUrl'),
			'data' => array(),
			'enctype' => 'application/x-www-form-urlencoded',
			'method' => 'post',
			'fields' => array(),
			'errorFields' => array(),
			'buttons' => array(
				//'submit' => array('label' => 'Submit', 'type' => 'submit'),
				//'cancel' => array('label' => 'Cancel', 'type' => 'button')
			),
		);
		foreach ($defaults as $k => $v) {
			if (!isset($def[$k])) $def[$k] = $v;
		}
		$def['classes'][] = 'form';
		$def['classes'][] = $def['id'];
		return $def;
	}
	
	protected function computeFields($def) {
		// token field
		$token = $this->getToken();
		$def['fields']['form-token'] = array('type' => 'hidden', 'entries' => array($token));
		// event field
		$def['fields']['form-event'] = array('type' => 'hidden', 'entries' => array(''));
		// fields
		$submitted = $this->submitted();
		foreach ($def['fields'] as $fieldId => $info) {
			if (is_string($info)) {
				$info = array('type' => 'plain', 'entries' => array($info));
			}
			$info['formId'] = $def['id'];
			if (!isset($info['type'])) {
				$info['type'] = 'text';
			}
			if (!isset($info['classes'])) {
				$info['classes'] = array();
			}
			if (!isset($info['className'])) {
				$info['className'] = '\trice\form\\' . StringUtils::camelCase($info['type']) . 'Field';
			}
			if (!isset($info['label'])) {
				$info['label'] = '';
			}
			if (!isset($info['entries'])) {
				$info['entries'] = $this->getFieldEntries($fieldId, $info, $def['data']);
			}
			// classes
			$info['classes'][] = 'field';
			$info['classes'][] = $fieldId;
			$info['classes'][] = $info['type'];
			if (in_array('req', $info)) $classes[] = 'req';
			if (in_array('multi', $info)) $classes[] = 'multi';
			// validation
			$error = false;
			$errorPos = false;
			if ($submitted) {
				// required fields
				if (in_array('req', $info)) {
					foreach ($info['entries'] as $pos => $entry) {
						$error = 'Required field';
						$errorPos = $pos;
						if (!StringUtils::isEmpty($entry)) {
							$error = false;
							$errorPos = 0;
							break;
						}
					}
				}
				// validation method
				if (!$error) {
					$cls = $info['className'];
					if (class_exists($cls)) {
						$obj = new $cls($fieldId, $info);
						list($error, $errorPos) = $obj->validate();
					}
				}
			}
			$info['error'] = $error;
			$info['errorPos'] = $errorPos;
			if ($error) {
				$def['errorFields'][] = $fieldId;
				$info['classes'][] = 'error';
			}
			// update changed field info
			$def['fields'][$fieldId] = $info;
		}
		return $def;
	}
	
	protected function getFieldEntries($fieldId, $fieldInfo, $data) {
		$request = Trice::getRequest();
		$result = null;
		// try data
		if (isset($data[$fieldId])) {
			return $data[$fieldId];
		}
		// try post
		$result = $request->get($fieldId, 'post');
		// try get
		if ($result === null) $result = $request->get($fieldId, 'get');
		// try files
		if ($result === null) $result = $request->get($fieldId, 'files');
		if ($result === null) {
			$result = '';// at least one value
		}
		if (!is_array($result)) {
			$result = array($result);
		}
		return $result;
	}
	
	protected function computeButtons($def) {
		$request = Trice::getRequest();
		foreach ($def['buttons'] as $id => $info) {
			if (is_string($info)) {
				$info = array('type' => 'button', 'value' => $info);
			}
			$info['formId'] = $def['id'];
			if (!isset($info['type'])) {
				$info['type'] = 'button';
			}
			if (!isset($info['classes'])) {
				$info['classes'] = array();
			}
			if (!isset($info['className'])) {
				$info['className'] = '\trice\form\\' . StringUtils::camelCase($info['type']) . 'Button';
			}
			$def['buttons'][$id] = $info;
		}
		return $def;
	}
	
	protected function getToken() {
		return Trice::getSession()->getToken();
	}

	public function getHtml() {
		$def = $this->getComputedDefinition();
		return '
			<form' . StringUtils::htmlClass($def['classes']) . ' id="' . $def['id'] . '" action="' . $def['action'] . '" method="' . $def['method'] . '" enctype="' . $def['enctype'] . '">
				' . $this->getFieldsHtml($def) . '
				' . $this->getMessageHtml($def) . '
				' . $this->getButtonsHtml($def) . '
			</form>
		';
	}
	
	protected function getFieldsHtml($def) {
		$r = '';
		foreach ($def['fields'] as $id => $info) {
			$r .= $this->getFieldHtml($id, $info);
		}
		return $r ? '<div class="fields">' . $r . '</div>' : '';
	}
	
	protected function getFieldHtml($id, $info) {
		$cls = $info['className'];
		if (class_exists($cls)) {
			$obj = new $cls($id, $info);
			return $obj->getHtml();
		}
		else {
			return "[$cls]";
		}
	}

	protected function getMessageHtml($def) {
		$msg = $this->message;
		if (!$msg && !empty($def['errorFields'])) {
			foreach ($def['errorFields'] as $fieldId) {
				$field = $def['fields'][$fieldId];
				$msg .= '
					<p class="error">
						' . "{$field['label']}: {$field['error']}" . '
					</p>
				';
			}
		}
		return $msg ? '<div class="message">' . $msg . '</div>' : '';
	}
	
	
	protected function getButtonsHtml($def) {
		$r = '';
		foreach ($def['buttons'] as $id => $info) {
			$r .= $this->getButtonHtml($id, $info);
		}
		return $r ? '<div class="buttons">' . $r . '</div>' : '';
	}
	
	protected function getButtonHtml($id, $info) {
		$cls = $info['className'];
		if (class_exists($cls)) {
			$obj = new $cls($id, $info);
			return $obj->getHtml();
		}
		else {
			return "[$cls]";
		}
	}
	
	public function getFieldValues($fieldId) {
		$def = $this->getComputedDefinition();
		return isset($def['fields'][$fieldId]) ? $def['fields'][$fieldId]['entries'] : array('');
	}
	
	public function getFieldValue($fieldId, $default = null) {
		$entries = $this->getFieldValues($fieldId);
		$entry = $entries[0];
		return StringUtils::isEmpty($entry) ? $default : $entry;
	}
	
}
