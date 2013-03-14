<?php
require_once 'FixedLayoutEPubPage.php';
require_once UTILITY_LIB . DIRECTORY_SEPARATOR . 'ArrayLib.php';
class FixedLayoutEPub {

	// contains FixedLayoutEPubPage objects 
	private $pages = array();

	private $metadata = array(); // hold string values only

	public $frontCover = null;
	public $backCover = null;

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
 */
	public function getMetadata() {
		return $this->metadata;
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
			$this->pages = ArrayLib::insert($this->pages, $addAtIndex, $page);
		}
	}
}