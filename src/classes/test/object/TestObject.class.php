<?php
namespace test\object;

class TestObject
{
    public function __construct(public string $testName, public bool $success = false, public mixed $returnElement = null, public mixed $errorElement = null)
    {
    }
    public function __toString(): string
    {
        return "Test: \"$this->testName\" Success: \"$this->success\" Return: \"$this->returnElement\" Error: \"$this->errorElement\"";
    }
}