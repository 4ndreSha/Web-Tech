<?php
//main
$title_page = "Better buy";
$style_file = "style/style.css";

//header
$logo_img = "images/logo.png";
$title = "Lya guitaristo!";
$buttonCount = 3;
$buttonData = array(
    array("acoustic.php", "electric.php", "bass.php"),
    array('ACOUSTIC', 'ELECTRIC', 'BASS')
);
$headerButtons = "";
for($i = 0; $i < $buttonCount; $i++) {
    $button = file_get_contents('templates/header-button.html');
    $button = str_replace('{link}', $buttonData[0][$i], $button);
    $button = str_replace('{name}', $buttonData[1][$i], $button);
    $headerButtons .= $button;
}

//guitar-shop-item
$itemCount = 3;
$itemData = array(
    array("acoustic.php", "electric.php", "bass.php"),
    array("Martin D-18E", "Fender Stratocaster", "RevOLite"), 
    array("images/1.png", "images/2.png", "images/3.png"),
    array("Изображение 1", "Изображение 2", "Изображение 3")
);
$guitarGallery = "";
for($i = 0; $i < $itemCount; $i++){
    $item = file_get_contents('templates/guitar-item.html');
    $item = str_replace('{link}', $itemData[0][$i], $item);
    $item = str_replace('{name}', $itemData[1][$i], $item);
    $item = str_replace('{image}', $itemData[2][$i], $item);
    $item = str_replace('{image-name}', $itemData[3][$i], $item);
    $guitarGallery .= $item;
}

//footer
$link_github = 'https://github.com/4ndreSha';
$img_github = "images/github.jpg";
$mail = 'Email: nesteruk0003@gmail.com';
$designer_name = '4ndreSha';

$main = file_get_contents('templates/main.html');

$main = str_replace('{guitar-gallery}', $guitarGallery, $main); //шаблон гитар

$main = str_replace('{title_page}', $title_page, $main);
$main = str_replace('{style_file}', $style_file, $main);


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

print $main;
