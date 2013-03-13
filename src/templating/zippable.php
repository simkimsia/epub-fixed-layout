<?php
function create_zip($files = array(), $destination = '', $overwrite = true) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { 
		return false; 
	}
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if ($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

		//close the zip -- done!
		$zip->close();

		//check to make sure the file exists
		return file_exists($destination);
	} else {
		return false;
	}
}

function convertAllEntriesToFiles($entries) {
	$files = array_map(function ($entry){
		if (is_dir($entry)) {
			return directoryToArray($entry, true);
		}
		return $entry;
	}, $entries);
	$result = array();
	array_walk_recursive($files, function ($value, $key) use (& $result) {
		if (!is_dir($value)) {
			$result[] = $value;
		}
	});
	return $result;
}

function directoryToArray($directory, $recursive) {
    $array_items = array();
    if ($handle = opendir($directory)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (is_dir($directory. "/" . $file)) {
                    if($recursive) {
                        $array_items = array_merge($array_items, directoryToArray($directory. "/" . $file, $recursive));
                    }
                    $file = $directory . "/" . $file;
                    $array_items[] = preg_replace("/\/\//si", "/", $file);
                } else {
                    $file = $directory . "/" . $file;
                    $array_items[] = preg_replace("/\/\//si", "/", $file);
                }
            }
        }
        closedir($handle);
    }
    return $array_items;
}

function filterBlacklist($files, $blacklist) {
	foreach ($files as $key=>$file) {
		if (strpos($file, $blacklist) !== false) {
			unset($files[$key]);
		}
	}
	return $files;
}

function zipIt($source, $destination, $include_dir = false, $additionalIgnoreFiles = array())
{
	// Ignore "." and ".." folders by default
	$defaultIgnoreFiles = array('.', '..');

	// include more files to ignore
	$ignoreFiles = array_merge($defaultIgnoreFiles, $additionalIgnoreFiles);

	if (!extension_loaded('zip') || !file_exists($source)) {
		return false;
	}

	if (file_exists($destination)) {
		unlink ($destination);
	}

	$zip = new ZipArchive();
		if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
		return false;
		}
	$source = str_replace('\\', '/', realpath($source));

	if (is_dir($source) === true)
	{

		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

		if ($include_dir) {

			$arr = explode("/",$source);
			$maindir = $arr[count($arr)- 1];

			$source = "";
			for ($i=0; $i < count($arr) - 1; $i++) { 
				$source .= '/' . $arr[$i];
			}

			$source = substr($source, 1);

			$zip->addEmptyDir($maindir);

		}

		foreach ($files as $file)
		{
			$file = str_replace('\\', '/', $file);

			// purposely ignore files that are irrelevant
			if( in_array(substr($file, strrpos($file, '/')+1), $ignoreFiles) )
				continue;

			$file = realpath($file);

			if (is_dir($file) === true)
			{
				$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
			}
			else if (is_file($file) === true)
			{
				$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
			}
		}
	}
	else if (is_file($source) === true)
	{
		$zip->addFromString(basename($source), file_get_contents($source));
	}

	return $zip->close();
}

function Zip($source, $destination)
{
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true)
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;

            $file = realpath($file);

            if (is_dir($file) === true)
            {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            }
            else if (is_file($file) === true)
            {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}