<?php

class FixedLayoutEPubPage {

	private $image = '';
	private $folderPath = '';
	private $mimeType = '';

	public function __construct($image, $mimeType = 'image/jpeg') {
		$this->image	= $image;
		$this->mimeType	= $mimeType;
	}

	public function getMIMEType() {
		$file		= $this->folderPath . DIRECTORY_SEPARATOR . $this->image;
		$file_info	= new finfo(FILEINFO_MIME);  // object oriented approach!
		$mime_type	= $file_info->buffer(file_get_contents($file));  // e.g. gives "image/jpeg"

		return $mime_type;
	}
}