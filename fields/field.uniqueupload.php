<?php

	if (!defined('__IN_SYMPHONY__')) die('<h2>Symphony Error</h2><p>You cannot directly access this file</p>');

	require_once(TOOLKIT . '/fields/field.upload.php');

	class FieldUniqueUpload extends FieldUpload {
		public function __construct(){
			parent::__construct();
			$this->_name = __('Unique File Upload');
		}

		public function checkPostFieldData($data, &$message, $entry_id = NULL) {
			if (is_array($data) and isset($data['name'])) $data['name'] = self::getUniqueFilename($data['name'], $entry_id);
			return parent::checkPostFieldData($data, $message, $entry_id);
		}

		public function processRawFieldData($data, &$status, &$message = NULL, $simulate = false, $entry_id = NULL) {
			if (is_array($data) and isset($data['name'])) $data['name'] = self::getUniqueFilename($data['name'], $entry_id);
			return parent::processRawFieldData($data, $status, $message, $simulate, $entry_id);
		}

		public function appendFormattedElement(&$wrapper, $data){
			parent::appendFormattedElement($wrapper, $data);
			$field = $wrapper->getChildrenByName($this->get('element_name'));
			if(!empty($field))
				end($field)->appendChild(new XMLElement('clean-filename', General::sanitize(self::getCleanFilename(basename($data['file'])))));
		}

		private static function getUniqueFilename($filename, $entry_id=NULL) {
			$result = $entry_id . '_' . uniqid() . '.' . pathinfo($filename, PATHINFO_EXTENSION);
			return $result;
		}

		private static function getCleanFilename($filename) {
			return preg_replace("/([^\/]*)(\-[a-f0-9]{13})(\.[^\.]+)$/", '$1$3', $filename);
		}
	}
