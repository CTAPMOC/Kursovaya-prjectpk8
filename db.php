<?php
$config = require __DIR__ . '/config.php';
$dsn = "mysql:host={$config['host']};port={$config['port']};" .
       "dbname={$config['database']};charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $config['user'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(503);
    exit('База данных недоступна. Проверьте config.php.');
}
