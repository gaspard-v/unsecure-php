<?php

require_once("src/test/database.php");

function launchAllTest()
{
    $errors = [];
    try {
        $dbTest = new test\Database();
        $dbTest->testAll();
    } catch (Exception $err) {
        array_push($errors, $err);
    }
    print_r($errors);
}
launchAllTest();