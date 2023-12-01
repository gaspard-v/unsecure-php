<?php
namespace test;

require_once("src/databaseOperation.php");
use DatabaseOperation;
use Exception;
use PDO;

class Database
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
        $this->databaseOperation->unsecureQuery("SELECT * FROM `user` WHERE username = :username", ["username" => [PDO::PARAM_STR, "user_clear_password"]]);
    }
    public function testAll()
    {
        $this->unsecureQuery();
    }
}