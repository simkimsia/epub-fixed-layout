<?php
error_reporting(E_ALL);
require_once 'constants.php';

require_once 'ZipLib.php';

require_once '../vendor/twig/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(OEBPS);

$cache = APP.DS.'cache';
$cache = false;
$twig = new Twig_Environment($loader, array(
	'cache' => $cache,
));

require_once '../lib/utility/UtilityExtension.php';

//use UtilityTwigExtension\Utility_Twig_Extension;
$twig->addExtension(new Utility_Twig_Extension());

$content = array(
	'title'		=> 'STORYDEX',
	'creator'	=> 'STORYZER',
	'publisher'	=> 'STORYZER',
	'date'		=> '2013',
	'language'	=> 'en',
	'book_id'	=> '#1234567890',
	
	'pages'		=> array(
			array('image' => 'PAGE1.jpg'),
			array('image' => 'PAGE2.jpg'),
		)
);

$fileToRender = 'content.opf';
$renderedOpf = $twig->render($fileToRender . '.html', $content);

$result = file_put_contents(SRC_EPUB_FILES . DS . $fileToRender, $renderedOpf);

if ($result !== false) {
	echo "successfully rendered content.opf<br />";
	$epubName = 'test1.epub';
	$options = array(
		'destination' => EPUB_FILES . DS . $epubName,
		'include_dir' => false,
	);

	$result = ZipLib::zipFolder(SRC_EPUB_FILES, $options);
	var_dump($result);
	if ($result !==false) {
		echo "successfully generated $epubName<br />";
	} else {
		echo "failed to generate $epubName<br />";
	}
} else {
	echo "failed to generate content.opf<br />";
}

