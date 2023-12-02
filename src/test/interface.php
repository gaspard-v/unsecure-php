<?php
namespace test\interface;

require_once("testObject.php");

use test\object\TestObject;
use Exception;

abstract class Test
{
    protected array $testFunctions;
    abstract public function __construct();
    /** testAll
     * 
     * execute all test functions
     * return an array of TestObject
     * @return array<TestObject>
     */
    public function testAll(): array
    {
        $returnArray = array_map(function ($testFunction) {
            [$functionName, $function] = $testFunction;
            $testObj = new TestObject($functionName);
            try {
                $testObj->returnElement = $function();
                $testObj->success = true;
            } catch (Exception $err) {
                $testObj->errorElement = $err;
            }
            return $testObj;
        }, $this->testFunctions);
        return $returnArray;
    }
}