<?php
define('SRC_FOLDER', __DIR__);
define('CLASSES_FOLDER', SRC_FOLDER . DIRECTORY_SEPARATOR . "classes");

$includeDirs = ["constant"];
$includeDirs = array_map(fn($dir) => SRC_FOLDER . DIRECTORY_SEPARATOR . $dir, $includeDirs);

spl_autoload_register(function ($class) {
    require_once CLASSES_FOLDER . DIRECTORY_SEPARATOR . $class . '.class.php';
});


$phpFiles = [];
foreach ($includeDirs as $dir) {
    $phpFiles = array_merge($phpFiles, glob($dir . DIRECTORY_SEPARATOR . '*.php'));
}

if (!$phpFiles)
    throw new Exception("No PHP files found in the " . SRC_FOLDER . " directory");

foreach ($phpFiles as $file) {
    require_once $file;
}
