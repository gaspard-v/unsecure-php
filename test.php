<?php

require_once("src/test/database.php");

function launchAllTest()
{
    $errors = [];
    try {
        $dbTest = new test\Database();
        $dbTest->testAll();
    } catch (Exception $err) {
        $errors += $err;
    }
    print_r($errors);
}
launchAllTest();