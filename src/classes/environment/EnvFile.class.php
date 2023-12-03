<?php
namespace environment;

abstract class EnvFile
{
    // Spécifiez le chemin vers votre fichier .env
    static private string $envFilePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '.env';

    static public function load(): void
    {
        // Vérifiez si le fichier .env n'existe pas
        if (!file_exists(self::$envFilePath)) {
            return;
        }
        // Lisez le contenu du fichier .env
        $envContent = file_get_contents(self::$envFilePath);

        // Divisez les lignes en un tableau
        $envLines = explode("\n", $envContent);

        // Parcourez chaque ligne
        foreach ($envLines as $line) {
            // Ignorez les lignes vides ou celles commençant par #
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }

            // Divisez chaque ligne en clé et valeur
            list($key, $value) = explode('=', $line, 2);

            // Supprimez les espaces blancs et les guillemets autour de la valeur
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");

            // Définissez la variable d'environnement
            putenv("$key=$value");
        }
    }
}