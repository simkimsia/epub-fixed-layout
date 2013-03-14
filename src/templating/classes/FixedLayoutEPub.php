<?php
require_once 'FixedLayoutEPubPage.php';

class FixedLayoutEPub {

	// contains FixedLayoutEPubPage objects 
	private $pages = array();

	private $metadata = array(); // hold string values only

	public function __construct($metadata = array()) {
		$this->_setDefaults();
		$this->updateMetadata($metadata);
	}

	protected function _setDefaults() {
		$this->metadata = array(
			'title'		=> '',
			'creator'	=> '',
			'publisher'	=> '',
			'date'		=> '',
			'language'	=> 'en',
			'book_id'	=> '',
		);
	}

/**
 *
 * append the new values for metadata into the current metadata
 */
	public function updateMetadata($metadata) {
		$this->metadata = array_merge($this->metadata, $metadata);
	}

/**
 *
 * append new page 
 */
	public function addPage(FixedLayoutEPubPage $page, $addAtIndex=null) {
		if ($addAtIndex == null) {
			// we just append to the pages
			$this->pages[] = $page;
		} else {

		}
	}
}