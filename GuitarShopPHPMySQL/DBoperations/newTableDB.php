<?php

$host = 'localhost';
$dbname = 'GuitarSite';
$username = 'root';
$password = '123456';

try {
    
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    $sql = "CREATE TABLE about( 
        id      INT AUTO_INCREMENT,
        guitar_type     VARCHAR(50) NOT NULL, 
        guitar_name     VARCHAR(50) NOT NULL, 
        img_link        VARCHAR(100) NOT NULL,
        content_link    VARCHAR(100) NOT NULL,
        PRIMARY KEY(id)
      );";
    // выполняем SQL-выражение
    $conn->exec($sql);
    echo "Table has been created";
}
catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}



/*
try {
    
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    $sql = "CREATE TABLE reviews( 
        id      INT AUTO_INCREMENT,
        r_name    VARCHAR(100) NOT NULL, 
        r_text    VARCHAR(500) NULL, 
        r_rank    VARCHAR(100) NULL,
        PRIMARY KEY(id)
      );";
    // выполняем SQL-выражение
    $conn->exec($sql);
    echo "Table reviews has been created";
}
catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
*/