<?php

require_once 'constants.php';

require_once 'ZipLib.php';


$options = array(
	'destination' => EPUB_FILES . DS . 'test1.epub',
	'include_dir' => false,
);

echo ZipLib::zipFolder(TESTTEMPLATE);