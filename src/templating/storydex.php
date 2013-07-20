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
    'title'     => 'STORYDEX',
    'creator'   => 'SHENZO',
    'publisher' => 'STORYZER',
    'date'      => '2013',
    'language'  => 'en',
    'book_id'   => 'storyzer1.0'
);

// third define the book you want to create an EPub for
$fixedLayoutEPub = new EPubHub_Book_FixedLayout($metadata, array('height' => 1700, 'width' => 1202));

// 4th define the pages for the book
$currentFolder = WORKS . DS . 'STORYDEX 1700x1202';
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

$result = false;
if (isset($_GET['in2'])) {
    $result = do2Separate($ePubHub, $fixedLayoutEPub);
}

if (isset($_GET['in1'])) {
    $result = doIn1Move($ePubHub, $fixedLayoutEPub);
}

$cloudStorage = '';
if (isset($_GET['cloud'])) {
    $cloudStorage = $_GET['cloud'];
}

if ($result !== false) {
    if ($cloudStorage == 'dropbox') {
        require_once('../vendor/benthedesigner/dropbox/lib/bootstrap.php');
        //require_once('../vendor/autoload.php');
        // Create a temporary file and write some data to it
        // $tmp = tempnam('/tmp', 'dropbox');
        // $data = 'This file was uploaded using the Dropbox API!';
        // file_put_contents($tmp, $data);

        try {
            // temporarily change to P001.png because it is smaller.
            $result = dirname($result) . DS . 'P001.png';
            // Upload the file with an alternative filename
            $put = $dropbox->putFile($result, basename($result));
            var_dump($put);
        } catch (\Dropbox\Exception\BadRequestException $e) {
            // The file extension is ignored by Dropbox (e.g. thumbs.db or .ds_store)
            echo 'Invalid file extension';
        }

        // Unlink the temporary file
        // unlink($tmp);
    } else if ($cloudStorage == 's3') {
        // this file is kept out of public repo because it contains sensitive
        // aws credentials. 
        // See http://docs.aws.amazon.com/aws-sdk-php-2/guide/latest/quick-start.html#creating-a-client
        // for example of its format.
        require '../vendor/s3bootstrap.php';

        // temporarily change to P001.png because it is smaller.
        $pathToFile = dirname($result) . DS . 'P001.png';
        $key = 'filename/1/' . basename($pathToFile);

        // Upload an object by streaming the contents of a file
        // $pathToFile should be absolute path to a file on disk
        $result = $client->putObject(array(
            'Bucket'     => $bucket,
            'Key'        => $key,
            'SourceFile' => $pathToFile,
            'ACL'           => 'public-read',
        ));

        // We can poll the object until it is accessible
        $client->waitUntilObjectExists(array(
            'Bucket' => $bucket,
            'Key'    => $key
        ));

        // public read is https://bucketname.s3.amazonaws.com/path/to/file.png
        var_dump($result); // 
    }
}
