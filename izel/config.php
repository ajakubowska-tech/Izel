<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "izel";

try {
    $pdo = new PDO(
        "mysql:host={$servername};dbname={$dbname};charset=utf8mb4",
        $username,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Błąd połączenia z bazą: " . $e->getMessage());
}