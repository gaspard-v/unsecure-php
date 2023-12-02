<?php
namespace test;

use test\object\TestObject;

require_once("src/databaseOperation.php");
use DatabaseOperation;
use Exception;
use PDO;
use test\interface\Test;

use function PHPSTORM_META\map;

class Database extends Test
{
    private DatabaseOperation $databaseOperation;
    public function __construct()
    {
        $this->testFunctions = ["unsecureQuery", fn() => $this->unsecureQuery()];
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
}