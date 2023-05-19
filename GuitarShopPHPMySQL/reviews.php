<?php

//Подключение к БД
$host = 'localhost';
$dbname = 'GuitarSite';
$username = 'root';
$password = '123456';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // установить режим обработки ошибок для PDO
} catch(PDOException $e) {
    echo "Database error: " . $e->getMessage();
}

$title_page = "Reviews";
$style_file = "style/style.css";

//header
$logo_img = "images/logo.png";
$title = "Lya guitaristo!";
$buttonData = array(
    array("about.php?type=Acoustic", 'ACOUSTIC'),
    array("about.php?type=Electric", 'ELECTRIC'),
    array("about.php?type=Bass", 'BASS'),
    array("reviews.php", 'REVIEWS')
);
$headerButtons = "";
foreach ($buttonData as $value) {
    $button = file_get_contents('templates/header-button.html');
    $button = str_replace('{link}', $value[0], $button);
    $button = str_replace('{name}', $value[1], $button);
    $headerButtons .= $button;
}

//footer
$link_github = 'https://github.com/4ndreSha';
$img_github = "images/github.jpg";
$mail = 'Email: nesteruk0003@gmail.com';
$designer_name = '4ndreSha';

$main = file_get_contents('templates/reviews.html');

$main= str_replace('{title_page}', $title_page, $main);
$main= str_replace('{style_file}', $style_file, $main);


$header = file_get_contents('templates/header.html');

$header = str_replace('{header-buttons}', $headerButtons, $header); //шаблон кнопок
$header = str_replace('{logo_img}',$logo_img,$header);
$header = str_replace('{title}',$title,$header);

$main= str_replace('{header}', $header, $main);


$footer = file_get_contents('templates/footer.html');


$footer = str_replace('{designer_name}', $designer_name,$footer);
$footer = str_replace('{link_github}', $link_github,$footer);
$footer = str_replace('{img_github}', $img_github,$footer);
$footer = str_replace('{mail}', $mail,$footer);

$main = str_replace('{footer}', $footer, $main);


//Тут начинается магия...
$tableName = 'reviews';

//$file_path = 'reviews.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $message = $_POST['message'];
    $rank = $_POST['rank'];

    $regex = "/(https?:\/\/(?!www\.bsuir\.by)(?!bsuir\.by)[\w\/:%#\$&\?\(\)~\.=\+\-]+)/i";
    $message = preg_replace($regex, "#Внешние ссылки запрещены#", $message);
    
    //Загоняем комментарий в таблицу в БД
    $data = [
        'r_name' => $name,
        'r_text' => $message,
        'r_rank' => $rank,
    ];
    $sql = "INSERT INTO reviews (r_name, r_text, r_rank) VALUES (:r_name, :r_text, :r_rank)";
    $stmt= $pdo->prepare($sql);
    $stmt->execute($data);
    
    //http://mysite.by https://www.mysite.by/price http://bsuir.by https://www.bsuir.by/ru/kafedry-bguir http://example.com
}

//Достаем из БД таблицу с комментариями
$sth = $pdo->prepare("SELECT * FROM `reviews` ORDER BY `id`");
$sth->execute();
$revArray = $sth->fetchAll(PDO::FETCH_ASSOC);

if($revArray != null) 
{
    $allReviews = "";
    foreach($revArray as $value)
    {
        $reviewItem = file_get_contents('templates/review-item.html');
        $reviewItem = str_replace('{name}', $value['r_name'], $reviewItem);
        $reviewItem = str_replace('{text}', $value['r_text'], $reviewItem);
        $reviewItem = str_replace('{rank}', $value['r_rank'], $reviewItem);
        $allReviews .= $reviewItem;
    }

    $main= str_replace('{review-item}', $allReviews, $main);
}
else
{
    $main= str_replace('{review-item}', "Здесь еще пока нет отзывов...", $main);
}



print $main;