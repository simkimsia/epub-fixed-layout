<?php
error_reporting(E_ALL);

require_once 'constants.php';

//require_once UTILITY_LIB . DS . 'ZipLib.php';

require_once '../vendor/epubhub/epubhub/lib/EPubHub/Autoloader.php';
EPubHub_Autoloader::register();

$twigLibPath = realpath('../vendor/twig/twig/lib/Twig');


// first define the rendering library to be used for EPubHub
$twigForEPubHub = new EPubHub_RenderingLibrary_Twig(
	$twigLibPath
);

// also define the zipping library to be used
$zipLibPath = realpath(UTILITY_LIB);

$zipLibForEPubHub = new EPubHub_ZippingLibrary_ZipLib($zipLibPath);


// 2nd define certain options for the EPubHub Environment
$ePubHubOptions = array(
    'debug'             => true
);
$ePubHub = new EPubHub_Environment($twigForEPubHub, $zipLibForEPubHub, $ePubHubOptions);


$metadata = array(
	'title'		=> 'STORYDEX',
	'creator'	=> 'STORYZER',
	'publisher'	=> 'STORYZER',
	'date'		=> '2013',
	'language'	=> 'en',
	'book_id'	=> '19910219'
);

// third define the book you want to create an EPub for
$fixedLayoutEPub = new EPubHub_Book_FixedLayout($metadata, array('height' => 1700, 'width' => 1202));

// 4th define the pages for the book
$frontCover = $image1 = new EPubHub_Image_FixedLayout(EPUB_IMAGES . DS . 'FRONT COVER.jpg'); 
$image2 = new EPubHub_Image_FixedLayout(EPUB_IMAGES . DS . 'PAGE1-intl.jpg');
$image3 = new EPubHub_Image_FixedLayout(EPUB_IMAGES . DS . 'PAGE2-intl.jpg');
$backCover = $image4 = new EPubHub_Image_FixedLayout(EPUB_IMAGES . DS . 'BACK COVER.jpg');
$page1 = new EPubHub_Page_FixedLayout($image1);
$page2 = new EPubHub_Page_FixedLayout($image2);
$page3 = new EPubHub_Page_FixedLayout($image3);
$page4 = new EPubHub_Page_FixedLayout($image4);

$fixedLayoutEPub->addPage($page1);
$fixedLayoutEPub->addPage($page2);
$fixedLayoutEPub->addPage($page3);
$fixedLayoutEPub->addPage($page4);
$fixedLayoutEPub->setFrontCover($frontCover);
$fixedLayoutEPub->setBackCover($backCover);

if (isset($_GET['in2'])) {
	do2Separate($ePubHub, $fixedLayoutEPub);
}

if (isset($_GET['in1'])) {
	doIn1Move($ePubHub, $fixedLayoutEPub);
}

