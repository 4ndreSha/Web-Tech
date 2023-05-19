<?php

$host = 'localhost';
$dbname = 'GuitarSite';
$username = 'root';
$password = '123456';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    //Our SQL statement. This will empty / truncate the table
    $sql = "TRUNCATE TABLE `reviews`";

    //Prepare the SQL query.
    $statement = $pdo->prepare($sql);

    //Execute the statement.
    $statement->execute();
    echo "Table truncated";
}
catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}