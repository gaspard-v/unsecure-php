<?php

namespace environmentVariables;

require_once("envFile.php");

use EnvFile;
use Exception;

abstract class _ENV_VARIABLE_SCHEMA
{
    public static string $env_variable_name = "";
    public static array $allowed_values = [];
    public static mixed $default_values = null;
}

abstract class _REQUIRE_METHOD extends _ENV_VARIABLE_SCHEMA
{
    public static string $env_variable_name = "REQUIRE_METHOD";
    public static array $allowed_values = ["SECURE", "UNSECURE"];
    public static mixed $default_values = "UNSECURE";
}

abstract class _PASSWORD_AUTH_TYPE extends _ENV_VARIABLE_SCHEMA
{
    public static string $env_variable_name = "PASSWORD_AUTH_TYPE";
    public static array $allowed_values = ["PLAIN", "MD5", "SECURE"];
    public static mixed $default_values = "PLAIN";
}

abstract class _MARIADB_HOST extends _ENV_VARIABLE_SCHEMA
{
    public static string $env_variable_name = "MARIADB_HOST";
    public static mixed $default_values = "localhost";
}
abstract class _MARIADB_USER extends _ENV_VARIABLE_SCHEMA
{
    public static string $env_variable_name = "MARIADB_USER";
}

abstract class _MARIADB_PASSWORD extends _ENV_VARIABLE_SCHEMA
{
    public static string $env_variable_name = "MARIADB_PASSWORD";
}

abstract class _MARIADB_DATABASE extends _ENV_VARIABLE_SCHEMA
{
    public static string $env_variable_name = "MARIADB_DATABASE";
}

abstract class _MARIADB_PORT extends _ENV_VARIABLE_SCHEMA
{
    public static string $env_variable_name = "MARIADB_PORT";
    public static mixed $default_values = "3306";
}

abstract class Environment
{
    static private array $classAssociation = [
        "REQUIRE_METHOD" => _REQUIRE_METHOD::class,
        "MARIADB_HOST" => _MARIADB_HOST::class,
        "MARIADB_USER" => _MARIADB_USER::class,
        "MARIADB_PASSWORD" => _MARIADB_PASSWORD::class,
        "MARIADB_DATABASE" => _MARIADB_DATABASE::class,
        "MARIADB_PORT" => _MARIADB_PORT::class,
    ];

    /**
     * Return the value of an environment variable
     * 
     * Return the value of an environment variable, 
     * or false if the environment variable does not exist
     * 
     * @param string $varName <p>The name of the environment variable</p>
     * @param array $option <p>[optional] options to change the behavior of the function.</p>
     * @return mixed
     */
    static function getEnv(
        string $varName,
        array $options = [
            "VAR_DEFINED" => true,
            "VAR_STRICT" => false,
        ]
    ): mixed {
        EnvFile::load();
        if (!in_array($varName, array_keys(self::$classAssociation))) { // no expected environment variable
            if ($options["VAR_DEFINED"]) // varName must be an expected environment variable. Throws an error
                throw new Exception("$varName must be an expected environment variable");
            if (getenv($varName) === false) {
                if ($$options["VAR_STRICT"])
                    throw new Exception("environment variable $varName does not exist");
                return false;
            }
            return getenv($varName);
        }

        $varClass = self::$classAssociation[$varName];

        if (getenv($varName) === false) // no environment variable defined, return default if exists, or throws errors
        {
            if (!$varClass::$default_values)
                throw new Exception("environment variable $varName does not have an default value");
            return $varClass::$default_values;
        }

        $varValue = getenv($varName);

        if ($varClass::$allowed_values && !in_array($varValue, $varClass::$allowed_values)) // ERROR: value of env variable is not in allowed_values !
            throw new Exception("the value of the environment variable $varName is not in allowed_values");

        return $varValue;
    }
}