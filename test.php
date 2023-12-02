<?php

require_once "src/requireAll.php";
use test\Database;

function launchAllTest()
{
    $dbTest = new Database();
    $result = $dbTest->testAll();
    print_r($result);
}
launchAllTest();