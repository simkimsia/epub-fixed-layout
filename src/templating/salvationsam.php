<?php
error_reporting(E_ALL);

require_once 'constants.php';
require_once 'functions.php';

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
    'title'     => 'Salvation Sam Issue 1',
    'creator'   => 'Aravind Menon',
    'publisher' => '',
    'date'      => '2013',
    'language'  => 'en',
    'book_id'   => 'salvationsam1.1'
);

// third define the book you want to create an EPub for
$fixedLayoutEPub = new EPubHub_Book_FixedLayout($metadata, array('height' => 1700, 'width' => 1119));

// 4th define the pages for the book
$currentFolder = WORKS . DS . 'salvationsamissue1' . DS . 'resized';
$files = array();
$ignoredFiles = array('.DS_Store', '.', '..');

if ($handle = opendir($currentFolder)) {
    echo "Directory handle: $handle<br />";
    echo "Entries:<br />";



    while (false !== ($entry = readdir($handle))) {

        if (isLegitFile($currentFolder, $entry, $ignoredFiles))
        {
            $files[] = $entry;
        }

        echo "$entry<br />";
    }

    closedir($handle);
}

// ensure the files are sorted by human natural numbering
sort($files, SORT_NATURAL | SORT_FLAG_CASE);

$length = count($files);

$start = 1;
$frontCoverAsFirstPage = true; // whether we want front cover to be first page as well
if ($frontCoverAsFirstPage)
{
    $start = 0;
}

for ($key = $start; $key < $length; $key++) {

    $entry = $files[$key];
    echo "added $entry<br />";
    $image = new EPubHub_Image_FixedLayout($currentFolder . DS . $entry);
    $page = new EPubHub_Page_FixedLayout($image);
    $fixedLayoutEPub->addPage($page);

}

$frontCover = new EPubHub_Image_FixedLayout($currentFolder . DS . $files[0]);

$fixedLayoutEPub->setFrontCover($frontCover);

$backCover = new EPubHub_Image_FixedLayout($currentFolder . DS . $files[$length - 1]);

$fixedLayoutEPub->setBackCover($backCover);


if (isset($_GET['in2'])) {
    do2Separate($ePubHub, $fixedLayoutEPub);
}

if (isset($_GET['in1'])) {
    doIn1Move($ePubHub, $fixedLayoutEPub);
}

