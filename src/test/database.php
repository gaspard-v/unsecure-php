<?php
namespace test;

require_once("../databaseOperation.php");
use DatabaseOperation;
use Exception;
use PDO;

class Database
{
    private DatabaseOperation $databaseOperation;
    function __construct()
    {
        try {
            $this->databaseOperation = new DatabaseOperation();
        } catch (Exception $err) {
            die($err);
        }
        $this->databaseOperation->unsecureQuery("SELECT * FROM `user` WHERE username = :username", ["username", [PDO::PARAM_STR, "user_clear_password"]]);
    }
}
new Database();