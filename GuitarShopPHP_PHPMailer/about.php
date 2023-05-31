<?php
session_start();

if(isset($_GET["type"])){
  
    $guitar_type = $_GET["type"];
}
else {
    header("Location: about.php?type=Acoustic");
}

//Подключение к БД
$host = 'localhost';
$dbname = 'GuitarSite';
$username = 'root';
$password = '123456';
$tableName = 'about';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // установить режим обработки ошибок для PDO

    //Достаем уникальные данные для странички
    $sth = $pdo->prepare("SELECT * FROM $tableName WHERE `guitar_type` = ?");
    $sth->execute(array($guitar_type));
    $arrayDB = $sth->fetch(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Database error: " . $e->getMessage();
}

//main
$title_page = $guitar_type;         //"Acoustic";
$style_file = "style/style.css";
$img_guitar = $arrayDB['img_link']; //"images/1.png";
$name = $arrayDB['guitar_name'];    //"Martin D-18E";

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

//Волшебная кнопка ^-^
$link_Out = "logOut.php?path=about.php?type=" . $_GET['type'];
$link_In = "auth.php";
$Text_In = "LOGIN";
$Text_Out = "LOGOUT";

if(!isset($_SESSION['user_id'])){

    $header = str_replace('{link_InOut}',$link_In,$header);
    $header = str_replace('{Text_InOut}',$Text_In,$header);
}
else{
    $header = str_replace('{link_InOut}',$link_Out,$header);
    $header = str_replace('{Text_InOut}',$Text_Out,$header);
}


$main= str_replace('{header}', $header, $main);

$text = file_get_contents($arrayDB['content_link']);    //"text/acoustic.txt"
$main = str_replace("{text}",$text, $main);

$footer = file_get_contents('templates/footer.html');

$footer = str_replace('{designer_name}', $designer_name,$footer);
$footer = str_replace('{link_github}', $link_github,$footer);
$footer = str_replace('{img_github}', $img_github,$footer);
$footer = str_replace('{mail}', $mail,$footer);

$main = str_replace('{footer}', $footer, $main);

print $main;
