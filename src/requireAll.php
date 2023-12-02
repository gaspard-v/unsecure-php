<?php

$srcFolder = __DIR__;
$phpFiles = glob($srcFolder . '*.php');

if ($phpFiles !== false) {
    foreach ($phpFiles as $file) {
        require_once $file;
    }
} else {
    echo "No PHP files found in the $srcFolder directory.\n";
}