<?php

$host = 'localhost';
$dbname = 'GuitarSite';
$username = 'root';
$password = '123456';

try {
    // подключаемся к серверу
    $conn = new PDO("mysql:host=$host", $username, $password);
     
    // SQL-выражение для создания базы данных
    $sql = "CREATE DATABASE $dbname";
    // выполняем SQL-выражение
    $conn->exec($sql);
    echo "Database has been created";
}
catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}