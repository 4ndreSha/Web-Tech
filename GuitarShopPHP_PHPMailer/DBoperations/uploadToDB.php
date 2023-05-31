<?php

$host = 'localhost';
$dbname = 'GuitarSite';
$username = 'root';
$password = '123456';
$tableName = 'about';

try {
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    //Загоняем данные в таблицу в БД
    $data = array( 
        array(
            'guitar_type' => "Acoustic",
            'guitar_name' => "Martin D-18E",
            'img_link' => "images/1.png",
            'content_link' => "text/acoustic.txt"
        ),
        array(
            'guitar_type' => "Electric",
            'guitar_name' => "Fender Stratocaster",
            'img_link' => "images/2.png",
            'content_link' => "text/electric.txt"
        ),
        array(
            'guitar_type' => "Bass",
            'guitar_name' => "RevOLite",
            'img_link' => "images/3.png",
            'content_link' => "text/bass.txt"
        ),
    );
    foreach($data as $value) {
        $sql = "INSERT INTO $tableName (guitar_type, guitar_name, img_link, content_link) VALUES (:guitar_type, :guitar_name, :img_link, :content_link)";
        $stmt= $pdo->prepare($sql);
        $stmt->execute($value);
    }

    echo "New rows added into table";
}
catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}


// Закрываем соединение с базой данных
$dbh = null;