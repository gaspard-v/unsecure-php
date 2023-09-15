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

    static private array $defaultOptions = [
        "VAR_DEFINED" => false,
        "VAR_STRICT" => false,
    ];
    /**
     * @param string $varName <p>The name of the environment variable</p>
     * @param array $option <p>options to change the behavior of the function</p>
     * @return mixed
     * 
     * options is an associative array
     */
    static function getEnv(string $varName, array $options = self::$defaultOptions): mixed
    {
        if (!in_array($varName, array_keys(self::$classAssociation))) { // no expected environment variable
            if ($options["VAR_DEFINED"]) // varName must be an expected environment variable. Throws an error
                throw new Exception("$varName must be an expected environment variable");
            if (!isset($_ENV[$varName])) {
                if ($$options["VAR_STRICT"])
                    throw new Exception("environment variable $varName does not exist");
                return false;
            }
            return $_ENV[$varName];
        }

        $varClass = self::$classAssociation[$varName];

        if (!isset($_ENV[$varName])) // no environment variable defined, return default if exists, or throws errors
        {
            if (!$varClass::$default_values)
                throw new Exception("environment variable $varName does not have an default value");
            return $varClass::$default_values;
        }

        $varValue = $_ENV[$varName];

        if (!in_array($varValue, $varClass::$allowed_values)) // ERROR: value of env variable is not in allowed_values !
            throw new Exception("the value of the environment variable $varName is not in allowed_values");

        return $varValue;
    }
}