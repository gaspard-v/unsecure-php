<?php

abstract class RequiredMethod {
    private static function secureRequireOnce(string $file): mixed
    {
        $allowedRequiredFiles = ["connexion.php", "verificationUtilisateur.php"];
        if(in_array($file, $allowedRequiredFiles))
            return require_once($file);
        return null;
    }
    static function requireOnce(string $file): mixed
    {
        return self::secureRequireOnce($file);
    }
}