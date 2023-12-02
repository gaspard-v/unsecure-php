<?php

require_once("src/test/database.php");

function launchAllTest()
{
    $dbTest = new test\Database();
    $result = $dbTest->testAll();
    print_r($result);
}
launchAllTest();