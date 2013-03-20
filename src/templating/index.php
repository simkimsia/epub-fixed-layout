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

function do2Separate($ePubHub, $fixedLayoutEPub)
{
	// 4th render the book into the files you want at the right place
	$result = $ePubHub->renderBook($fixedLayoutEPub, SRC_EPUB_FILES);

	if ($result !== false) {
		echo "successfully rendered content.opf<br />";
		// 5th zip the rendered files into the epub you want
		$result		= $ePubHub->zipRendered(SRC_EPUB_FILES, EPUB_FILES);
		$metadata	= $fixedLayoutEPub->getMetadata();
		$filename	= $metadata['title'] . '.epub';

		if ($result !==false) {
			echo "successfully generated $filename<br />";
		} else {
			echo "failed to generate $filename<br />";
		}
	} else {
		echo "failed to generate content.opf<br />";
	}
}

function doIn1Move($ePubHub, $fixedLayoutEPub) {
	// OR makeEPub to replace step 4 and 5
	$result = $ePubHub->makeEPub($fixedLayoutEPub, EPUB_FILES);
	$metadata	= $fixedLayoutEPub->getMetadata();
	$filename	= $metadata['title'] . '.epub';

	if ($result !==false) {
		echo "successfully generated $filename<br />";
	} else {
		echo "failed to generate $filename<br />";
	}
}

