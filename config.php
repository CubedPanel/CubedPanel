<?php
require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

$configFile = '/etc/cubed/config.yml';

try {
    if (!file_exists($configFile)) {
        throw new Exception("Fichier de config introuvable.");
    }

    $config = Yaml::parseFile($configFile);

    $host = $config['database']['host'];
    $dbname = $config['database']['name'];
    $username = $config['database']['user'];
    $password = $config['database']['password'];

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Exception $e) {
    die($e->getMessage());
}
