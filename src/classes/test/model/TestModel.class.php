<?php
namespace test\model;

use test\object\TestObject;
use Exception;

abstract class TestModel
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
        $returnArray = array_map(function ($functionName, $function) {
            $testObj = new TestObject($functionName);
            try {
                $testObj->returnElement = $function();
                $testObj->success = true;
            } catch (Exception $err) {
                $testObj->errorElement = $err;
            }
            return $testObj;
        }, array_keys($this->testFunctions), $this->testFunctions);
        return $returnArray;
    }
}