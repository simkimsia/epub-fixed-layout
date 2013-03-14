<?php

define('DS', DIRECTORY_SEPARATOR);
define('APP', getcwd());

define('BUILD', dirname(dirname(APP)) . DS . 'build');
define('SRC', dirname(dirname(APP)) . DS . 'src');
define('UTILITY_LIB', SRC . DS . 'Plugin' . DS . 'UtilityLib' . DS . 'Lib');
define('OUTPUT', BUILD . DS . 'output');
define('SRC_EPUB_FILES', OUTPUT . DS . 'src_epub_files');
define('EPUB_FILES', OUTPUT . DS . 'epub_files');

define('TESTTEMPLATE', dirname(APP) . DS . 'testtemplate');

define('TEMPLATES', dirname(APP) . DS . 'templates');
define('OEBPS', TEMPLATES . DS . 'OEBPS');
define('EPUB_IMAGES', OEBPS . DS . 'Images');
define('EPUB_STYLES', OEBPS . DS . 'Styles');
define('EPUB_TEXT', OEBPS . DS . 'Text');
define('META_INF', TEMPLATES . DS . 'META-INF');

