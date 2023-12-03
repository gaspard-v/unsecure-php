<?php
use environment\EnvironmentVariables;

abstract class RequiredMethod
{
    private const requireAssociation = [
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
        $requireMethod = EnvironmentVariables::getEnv("REQUIRE_METHOD");
        $pointer = self::requireAssociation[$requireMethod];
        return call_user_func([RequiredMethod::class, $pointer], $file);
    }
}