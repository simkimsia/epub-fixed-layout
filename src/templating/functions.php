<?php

function do2Separate($ePubHub, $fixedLayoutEPub)
{
    // 4th render the book into the files you want at the right place
    $result = $ePubHub->renderBook($fixedLayoutEPub, SRC_EPUB_FILES);

    if ($result !== false) {
        echo "successfully rendered content.opf<br />";
        // 5th zip the rendered files into the epub you want
        $result     = $ePubHub->zipRendered(SRC_EPUB_FILES, EPUB_FILES);
        $metadata   = $fixedLayoutEPub->getMetadata();
        $filename   = $metadata['title'] . '.epub';

        if ($result !==false) {
            echo "successfully generated $filename<br />";
        } else {
            echo "failed to generate $filename<br />";
        }
    } else {
        echo "failed to generate content.opf<br />";
    }
    return $result;
}

function doIn1Move($ePubHub, $fixedLayoutEPub) {
    // OR makeEPub to replace step 4 and 5
    $result = $ePubHub->makeEPub($fixedLayoutEPub, EPUB_FILES);
    $metadata   = $fixedLayoutEPub->getMetadata();
    $filename   = $metadata['title'] . '.epub';

    if ($result !==false) {
        echo "successfully generated $filename<br />";
    } else {
        echo "failed to generate $filename<br />";
        return false;
    }
    return EPUB_FILES  . DS . $filename;
}

function isLegitFile($path, $entry, $ignoredFiles)
{
    if (is_file($path . '/' . $entry) && !in_array($entry, $ignoredFiles))
    {
        return true;
    }
    return false;
}