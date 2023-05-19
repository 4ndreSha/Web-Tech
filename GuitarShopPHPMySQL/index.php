<?php
//main
$title_page = "Better buy";
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

//guitar-shop-item
$itemData = array(
    array("acoustic.php", "Martin D-18E", "images/1.png", "Изображение 1"),
    array("electric.php", "Fender Stratocaster", "images/2.png", "Изображение 2"), 
    array("bass.php", "RevOLite", "images/3.png", "Изображение 3")
);
$guitarGallery = "";
foreach($itemData as $value) {
    $item = file_get_contents('templates/guitar-item.html');
    $item = str_replace('{link}', $value[0], $item);
    $item = str_replace('{name}', $value[1], $item);
    $item = str_replace('{image}', $value[2], $item);
    $item = str_replace('{image-name}', $value[3], $item);
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
