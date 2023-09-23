<?php

$dbConfig = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbname' => 'appdevdb',
];

try {
    $pdo = new PDO(
        "mysql:host={$dbConfig['host']};
        dbname={$dbConfig['dbname']}",
        $dbConfig['username'],
        $dbConfig['password']
);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET CHARACTER SET utf8");
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
