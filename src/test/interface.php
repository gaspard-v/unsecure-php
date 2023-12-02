<?php
namespace test\interface;

require_once("testObject.php");

use test\object\TestObject;

interface Test
{
    public function __construct();
    /** testAll
     * 
     * execute all test functions
     * return an array of TestObject
     * @return array<TestObject>
     */
    public function testAll(): array;
}