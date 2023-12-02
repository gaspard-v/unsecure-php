<?php
namespace test;

use test\object\TestObject;

require_once("src/databaseOperation.php");
use DatabaseOperation;
use Exception;
use PDO;
use test\interface\Test;

use function PHPSTORM_META\map;

class Database implements Test
{
    private DatabaseOperation $databaseOperation;
    public function __construct()
    {
        try {
            $this->databaseOperation = new DatabaseOperation();
        } catch (Exception $err) {
            die($err);
        }

    }
    public function unsecureQuery()
    {
        return $this->databaseOperation->unsecureQuery("SELECT * FROM `user` WHERE username = :username", ["username" => [PDO::PARAM_STR, "user_clear_password"]]);
    }
    public function testAll(): array
    {
        $testFunctions = ["unsecureQuery", fn() => $this->unsecureQuery()];
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
        }, $testFunctions);
        return $returnArray;
    }
}