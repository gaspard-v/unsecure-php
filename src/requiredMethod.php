<?php

require_once("allowedIncludeFiles.php");
require_once("environmentVariables.php");
use environmentVariables\Environment;

abstract class RequiredMethod
{
    const requireAssociation = [
        "SECURE" => "secureRequireOnce",
        "UNSECURE" => "unsecureRequireOnce",
    ];
    static function secureRequireOnce(string $file): mixed
    {
        $allowedRequiredFiles = ALLOWED_INCLUDE_FILES;
        if (in_array($file, $allowedRequiredFiles))
            return require_once($file);
        return null;
    }
    static function unsecureRequireOnce(string $file): mixed
    {
        return require_once($file);
    }
    static function requireOnce(string $file): mixed
    {
        $requireMethod = Environment::getEnv("REQUIRE_METHOD");
        $pointer = self::requireAssociation[$requireMethod];
        return call_user_func([RequiredMethod::class, $pointer], $file);
    }
}