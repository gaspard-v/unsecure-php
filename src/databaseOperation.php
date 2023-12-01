<?php

require_once("environmentVariables.php");
use environmentVariables\Environment;

class DatabaseOperation
{
    private PDO $conn;
    function __construct()
    {
        $server = Environment::getEnv("MARIADB_HOST");
        $user = Environment::getEnv("MARIADB_USER");
        $password = Environment::getEnv("MARIADB_PASSWORD");
        $database = Environment::getEnv("MARIADB_DATABASE");
        $port = Environment::getEnv("MARIADB_PORT");
        $this->conn = new PDO("mysql:host=$server;dbname=$database;port=$port", $user, $password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn->setAttribute(PDO::ATTR_AUTOCOMMIT, FALSE);
    }
    function exec(string $sql): bool|int
    {
        try {
            $this->conn->beginTransaction();
            $ret = $this->conn->exec($sql);
            $this->conn->commit();
        } catch (PDOException $e) {
            $this->conn->rollBack();
            die($e->getMessage());
        }
        return $ret;
    }
    function secureQuery(string $query, array $parameters): array
    {
        try {
            $this->conn->beginTransaction();
            $request = $this->conn->prepare($query);
            foreach ($parameters as $key => [$type, $value]) {
                $request->bindParam($key, $value, $type);
            }
            $request->execute();
            $ret = $request->fetchAll();
            $this->conn->commit();
        } catch (PDOException $e) {
            $this->conn->rollBack();
            die($e->getMessage());
        }
        return $ret;
    }

    function unsecureQuery(string $query, array $parameters): array
    {
        $arrayValues = array_map(function (array $param) {
            $quotableType = [PDO::PARAM_STR, PDO::PARAM_STR_CHAR, PDO::PARAM_STR_NATL];
            [$type, $value] = $param;
            if (in_array($type, $quotableType))
                return "'$value'";
            return $value;
        }, $parameters);

        $normalizedParameters = array_map(function (string $param) {
            return ":$param";
        }, array_keys($parameters));

        $query = str_replace(
            $normalizedParameters,
            $arrayValues,
            $query
        );
        try {
            $this->conn->beginTransaction();
            $request = $this->conn->query($query);
            $ret = $request->fetchAll();
            $this->conn->commit();
        } catch (PDOException $e) {
            $this->conn->rollBack();
            die($e->getMessage());
        }
        return $ret;
    }
}