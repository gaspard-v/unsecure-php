<?php

use environment\EnvironmentVariables;

class DatabaseOperation
{
    private PDO $conn;
    function __construct()
    {
        $server = EnvironmentVariables::getEnv("MARIADB_HOST");
        $user = EnvironmentVariables::getEnv("MARIADB_USER");
        $password = EnvironmentVariables::getEnv("MARIADB_PASSWORD");
        $database = EnvironmentVariables::getEnv("MARIADB_DATABASE");
        $port = EnvironmentVariables::getEnv("MARIADB_PORT");
        $this->conn = new PDO("mysql:host=$server;dbname=$database;port=$port", $user, $password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn->setAttribute(PDO::ATTR_AUTOCOMMIT, FALSE);
    }
    /**
     * Execute an SQL statement and return the number of affected rows
     * 
     * @param string $sql
     * @throws PDOException
     * @return int|false
     * PDO::exec returns the number of rows that were modified or deleted by the SQL statement you issued. If no rows were affected, PDO::exec returns 0.
     */
    function exec(string $sql): int|false
    {
        try {
            $this->conn->beginTransaction();
            $ret = $this->conn->exec($sql);
            $this->conn->commit();
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
        return $ret;
    }
    /**
     * Executes a secure SQL statement, returning a result set as an array containing all of the rows in the result set
     * 
     * @param string $query
     * Must be a prepared statement. i.e SELECT * FROM `user` WHERE password = :password and username = :username
     * @param array $parameters
     * Parameters for the prepared SQL statement. the array is like ["my_attribute" => [PDO::PARAM_*, "my_value"]].
     * i.e ["password" => [PDO::PARAM_STR, "my_password"], "username" => [PDO::PARAM_STR, "my_username"]]
     * @throws PDOException
     * @return array
     * returns an array containing all of the remaining rows in the result set.
     * The array represents each row as either an array of column values or an object with properties corresponding to each column name.
     * An empty array is returned if there are zero results to fetch
     */
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
            throw $e;
        }
        return $ret;
    }

    /**
     * Executes an unsecure SQL statement, returning a result set as an array containing all of the rows in the result set
     * 
     * @param string $query
     * Must be a prepared statement. i.e SELECT * FROM `user` WHERE password = :password and username = :username
     * @param array $parameters
     * Parameters for the prepared SQL statement. the array is like ["my_attribute" => [PDO::PARAM_*, "my_value"]].
     * i.e ["password" => [PDO::PARAM_STR, "my_password"], "username" => [PDO::PARAM_STR, "my_username"]]
     * @throws PDOException
     * @return array
     * returns an array containing all of the remaining rows in the result set.
     * The array represents each row as either an array of column values or an object with properties corresponding to each column name.
     * An empty array is returned if there are zero results to fetch
     */
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
            throw $e;
        }
        return $ret;
    }
}