<?php

namespace environmentVariables;

use Exception;

abstract class _ENV_VARIABLE_SCHEMA
{
    static protected string $env_variable_name;
    static protected array $allowed_values;
    static protected mixed $default_values;
}

abstract class _REQUIRE_METHOD extends _ENV_VARIABLE_SCHEMA
{
    static string $env_variable_name = "REQUIRE_METHOD";
    static array $allowed_values = ["SECURE", "UNSECURE"];
    static mixed $default_values = "UNSECURE";
}

abstract class Environment
{
    static private array $classAssociation = [
        "REQUIRE_METHOD" => _REQUIRE_METHOD::class
    ];
    static function getEnv($varName, $defined = true, $strict = false): mixed
    {
        if (!in_array($varName, array_keys(self::$classAssociation))) { // no expected environment variable
            if ($defined) // varName must be an expected environment variable. Throws an error
                throw new Exception("varName must be an expected environment variable");
            if (!isset($_ENV[$varName])) {
                if ($strict)
                    throw new Exception("environment variable $varName doest not exist");
                return false;
            }
            return $_ENV[$varName];
        }

        $varClass = self::$classAssociation[$varName];

        if (!isset($_ENV[$varName])) // no environment variable defined, return default
            return $varClass::$default_values;

        $varValue = $_ENV[$varName];

        if (!in_array($varValue, $varClass::$allowed_values)) // ERROR: value of env variable is not in allowed_values !
            throw new Exception("the value of the environment variable $varName is not in allowed_values");

        return $varValue;
    }
}