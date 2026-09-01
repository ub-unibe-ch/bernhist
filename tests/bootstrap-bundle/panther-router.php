<?php

// Symfony Panther's PHP built-in web server does not serve static files
// (compiled AssetMapper CSS/JS, fonts, images) unless a router script checks
// for them first - without this, every request falls through to the Symfony
// kernel and static assets 404 during Panther test runs.
// See https://github.com/symfony/panther/issues/630

if (is_file($_SERVER['DOCUMENT_ROOT'].\DIRECTORY_SEPARATOR.$_SERVER['SCRIPT_NAME'])) {
    return false;
}

$script = 'index.php';

$_SERVER = array_merge($_SERVER, $_ENV);
$_SERVER['SCRIPT_FILENAME'] = $_SERVER['DOCUMENT_ROOT'].\DIRECTORY_SEPARATOR.$script;
$_SERVER['SCRIPT_NAME'] = \DIRECTORY_SEPARATOR.$script;
$_SERVER['PHP_SELF'] = \DIRECTORY_SEPARATOR.$script;

require $script; // @phpstan-ignore require.fileNotFound
