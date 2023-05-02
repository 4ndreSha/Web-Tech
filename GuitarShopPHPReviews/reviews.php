<?php

$title_page = "Reviews";
$style_file = "style/style.css";

//header
$logo_img = "images/logo.png";
$title = "Lya guitaristo!";
$buttonCount = 4;
$buttonData = array(
    array("acoustic.php", "electric.php", "bass.php", "reviews.php"),
    array('ACOUSTIC', 'ELECTRIC', 'BASS', 'REVIEWS')
);
$headerButtons = "";
for($i = 0; $i < $buttonCount; $i++) {
    $button = file_get_contents('templates/header-button.html');
    $button = str_replace('{link}', $buttonData[0][$i], $button);
    $button = str_replace('{name}', $buttonData[1][$i], $button);
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


$file_path = 'reviews.txt';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $message = $_POST['message'];
    $rank = $_POST['rank'];

    $regex = "/(https?:\/\/(?!www\.bsuir\.by)(?!bsuir\.by)[\w\/:%#\$&\?\(\)~\.=\+\-]+)|(<script[\s\S]*?<\/script>)/i";
    $message = preg_replace($regex, "#Внешние ссылки запрещены#", $message);
    

    // $review = "$name: $message\n";
    $review = array($name, $message, $rank);
    $revArray = file_get_contents($file_path);
    if($revArray != null)
    {
        $revArray = unserialize($revArray);
        array_push($revArray, $review);
    }
    else
    {
        $revArray = array($review);
    }

    //http://mysite.by https://www.mysite.by/price http://bsuir.by https://www.bsuir.by/ru/kafedry-bguir http://example.com
    $file = fopen($file_path, "w");
    $revArray = serialize($revArray);
    fwrite($file, $revArray);
    fclose($file);
}

$revArray= file_get_contents($file_path);
if($revArray != null) 
{
    $revArray = unserialize($revArray);
    $allReviews = "";
    foreach($revArray as $value)
    {
        $reviewItem = file_get_contents('templates/review-item.html');
        $reviewItem = str_replace('{name}', $value[0], $reviewItem);
        $reviewItem = str_replace('{text}', $value[1], $reviewItem);
        $reviewItem = str_replace('{rank}', $value[2], $reviewItem);
        $allReviews .= $reviewItem;
    }

    $main= str_replace('{review-item}', $allReviews, $main);
}
else
{
    $main= str_replace('{review-item}', "Здесь еще пока нет отзывов...", $main);
}



print $main;