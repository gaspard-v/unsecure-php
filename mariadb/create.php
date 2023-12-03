<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "requireAll.php";
use environment\EnvironmentVariables;

$server = EnvironmentVariables::getEnv("MARIADB_HOST");
$user = EnvironmentVariables::getEnv("MARIADB_USER");
$password = EnvironmentVariables::getEnv("MARIADB_PASSWORD");
$database = EnvironmentVariables::getEnv("MARIADB_DATABASE");
$port = EnvironmentVariables::getEnv("MARIADB_PORT");

$conn = new PDO("mysql:host=$server;dbname=$database;port=$port", $user, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_AUTOCOMMIT, FALSE);

$sql = file_get_contents("schema.sql");

$clear_password = 'my_password';
$md5_password = md5($clear_password);
$secure_password = password_hash($clear_password, PASSWORD_DEFAULT);

try {
    $conn->beginTransaction();
    $conn->exec($sql);
    $sql = "INSERT INTO `user` (`username`, `password`, `balance`) 
            VALUES ('user_clear_password', 'my_password', '123456.50'), 
                   ('user_md5_password', '$md5_password', '654321.99'), 
                   ('user_secure_password', '$secure_password', '456789.00')";
    $conn->exec($sql);
    $conn->commit();
    echo "Table and data have been created";
} catch (PDOException $e) {
    $message = $e->getMessage();
    $conn->rollBack();
    die($message);
}