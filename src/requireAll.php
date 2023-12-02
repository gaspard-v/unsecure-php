<?php
$srcFolder = __DIR__;
$phpFiles = glob($srcFolder . DIRECTORY_SEPARATOR . '*.php');
$phpFiles = array_merge($phpFiles, glob($srcFolder . DIRECTORY_SEPARATOR . "schema" . DIRECTORY_SEPARATOR . '*.php'));
$phpFiles = array_merge($phpFiles, glob($srcFolder . DIRECTORY_SEPARATOR . "test" . DIRECTORY_SEPARATOR . '*.php'));

if ($phpFiles !== false) {
    foreach ($phpFiles as $file) {
        require_once $file;
    }
} else {
    echo "No PHP files found in the $srcFolder directory.\n";
}