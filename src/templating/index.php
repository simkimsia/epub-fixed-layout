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
/*
var_dump($renderedOpf);


<!--
    {% for page in pages %}
        {% set pagenumber = padLeadingZero(loop.index1, 3) %}
        {% set pagename = 'page' ~ pagenumber ~ '.xhtml' %}
        {% set pageitemid = 'pg' ~ pagenumber %}
        {% set pagepath = 'Text/' ~ pagename %}
        {% set image = page.image %}
        {% set imagepath = 'Images/' ~ image %}
        {% set imageitemid = pageitemid ~ '-image' %}
        <item href="{{ pagepath  }}" id="{{ pageitemid }}" media-type="application/xhtml+xml"/>
        <item href="{{ imagepath }}" id="{{ imageitemid }}" media-type="image/jpeg"/>
    {% endfor %} -->

        <!--<item href="Images/PAGE1.jpg" id="page01-image" media-type="image/jpeg"/>
        <item href="Images/PAGE1-intl.jpg" id="page01-intl-image" media-type="image/jpeg"/>
        <item href="Images/PAGE2.jpg" id="page02-image" media-type="image/jpeg"/>
        <item href="Images/PAGE2-intl.jpg" id="page02-intl-image" media-type="image/jpeg"/>

        <item href="Images/BACK COVER.jpg" id="bc-image" media-type="image/jpeg"/>

        <item href="Images/BACK OF BACK COVER.jpg" id="bobc-image" media-type="image/jpeg"/>-->

*/
$result = file_put_contents(SRC_EPUB_FILES . DS . $fileToRender, $renderedOpf);

var_dump($result);
/*
if ($result !== false) {
	$options = array(
		'destination' => EPUB_FILES . DS . 'test1.epub',
		'include_dir' => false,
	);

	echo ZipLib::zipFolder(TESTTEMPLATE);
}*/

echo 'success';