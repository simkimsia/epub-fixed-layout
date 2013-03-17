<?php
error_reporting(E_ALL);

require_once 'constants.php';

require_once UTILITY_LIB . DS . 'ZipLib.php';

require_once '../vendor/epubhub/epubhub/lib/EPubHub/Autoloader.php';
EPubHub_Autoloader::register();

$twigLibPath = realpath('../vendor/twig/twig/lib/Twig');

// first define the rendering library to be used for EPubHub
$twigForEPubHub = new EPubHub_RenderingLibrary_Twig(
	$twigLibPath
);

// also define the zipping library to be used
// $zipForEPubHub = new.....

// 2nd define certain options for the EPubHub Environment
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

// third define the book you want to create an EPub for
$fixedLayoutEPub = new EPubHub_Book_FixedLayout($metadata);

$pages = array(
	array('image' => 'PAGE1.jpg'),
	array('image' => 'PAGE2.jpg'),
);

// 4th render the book into the files you want at the right place
$result = $ePubHub->renderBook($fixedLayoutEPub, SRC_EPUB_FILES);

// 5th zip the rendered files into the epub you want
// $result = $ePubHub->zipRendered(SRC_EPUB_FILES, $buildFolder);

// OR makeEPub to replace step 4 and 5
// $result = $ePubHub->makeEPub($fixedLayoutEPub, $source, $build);

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