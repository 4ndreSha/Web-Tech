<?php
//main
$title_page = "Electric";
$style_file = "style/style.css";
$img_guitar = "images/2.png";
$name = "Fender Stratocaster";

//header
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

//footer
$link_github = 'https://github.com/4ndreSha';
$img_github = "images/github.jpg";
$mail = 'Email: nesteruk0003@gmail.com';
$designer_name = '4ndreSha';



$main = file_get_contents('templates/about.html');

$main= str_replace('{title_page}', $title_page, $main);
$main= str_replace('{style_file}', $style_file, $main);
$main= str_replace('{img_guitar}', $img_guitar, $main);
$main= str_replace('{name}', $name, $main);



$header = file_get_contents('templates/header.html');

$header = str_replace('{header-buttons}', $headerButtons, $header); //шаблон кнопок
$header = str_replace('{logo_img}',$logo_img,$header);
$header = str_replace('{title}',$title,$header);
//$header = str_replace('{title_stout}',$link_stout,$header);

$main= str_replace('{header}', $header, $main);

$text = file_get_contents("text/electric.txt");
$main = str_replace("{text}",$text, $main);

$footer = file_get_contents('templates/footer.html');

$footer = str_replace('{designer_name}', $designer_name,$footer);
$footer = str_replace('{link_github}', $link_github,$footer);
$footer = str_replace('{img_github}', $img_github,$footer);
$footer = str_replace('{mail}', $mail,$footer);

$main = str_replace('{footer}', $footer, $main);



print $main;
