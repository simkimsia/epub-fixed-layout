<?php
error_reporting(E_ALL);

require_once 'constants.php';

require_once UTILITY_LIB . DS . 'ZipLib.php';

require_once '../vendor/epubhub/epubhub/lib/EPubHub/Autoloader.php';
EPubHub_Autoloader::register();

$twigLibPath = realpath('../vendor/twig/twig/lib/Twig');


$twigForEPubHub = new EPubHub_RenderingLibrary_Twig(
	$twigLibPath
);


$ePubHubOptions = array(
    'debug'             => true
);
$ePubHub = new EPubHub_Environment($twigForEPubHub, null, $ePubHubOptions);



$metadata = array(
	'title'		=> 'STORYDEX',
	'creator'	=> 'STORYZER',
	'publisher'	=> 'STORYZER',
	'date'		=> '2013',
	'language'	=> 'en',
	'book_id'	=> '#1234567890'
);


$epub = new EPubHub_Book_FixedLayout($metadata);

$pages = array(
	array('image' => 'PAGE1.jpg'),
	array('image' => 'PAGE2.jpg'),
);


$result = $ePubHub->renderBook($epub, SRC_EPUB_FILES);

if ($result !== false) {
	echo "successfully rendered content.opf<br />";
	$epubName = 'test1.epub';
	$options = array(
		'destination' => EPUB_FILES . DS . $epubName,
		'include_dir' => false,
	);

	$result = ZipLib::zipFolder(SRC_EPUB_FILES, $options);
	
	if ($result !==false) {
		echo "successfully generated $epubName<br />";
	} else {
		echo "failed to generate $epubName<br />";
	}
} else {
	echo "failed to generate content.opf<br />";
}