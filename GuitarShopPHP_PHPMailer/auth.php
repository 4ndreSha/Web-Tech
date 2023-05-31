<?php
session_start();

$warning = "";
if(isset($_GET["state"])){
  
    if($_GET["state"] == "Success"){
        $warning = '<div class="success">Регистрация прошла успешно</div>';
    }
    if($_GET["state"] == "Login_warning") {
        $warning = '<div class="warning">Неверный логин или пароль</div>';
    }
    if($_GET["state"] == "Register_warning") {
        $warning = '<div class="warning">Такой пользователь уже зарегистрирован</div>';
    }
}

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

//main
$title_page = "Authentication";
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

$main = file_get_contents('templates/auth.html');

$main = str_replace('{title_page}', $title_page, $main);
$main = str_replace('{style_file}', $style_file, $main);


$head_title = "Вход на сайт";
$b_name = 'login';
$b_value = 'Вход';
$b_switch_title = 'Еще нет аккаунта?';
$b_switch_link = "auth.php?switch=Register";
$switch_link = '';
if(isset($_GET["switch"])){
    if($_GET["switch"] == "Register"){
        $reg_field = file_get_contents('templates/register-field.html');
        $main = str_replace('{reg_name}', $reg_field, $main);
        
        $head_title = "Регистрация";
        $b_name = 'register';
        $b_value = 'Регистрация';
        $b_switch_title = 'Уже есть аккаунт?';
        $b_switch_link = 'auth.php';
        $switch_link = "&switch=Register";
    }
}
$main = str_replace('{reg_name}', '', $main);
$main = str_replace('{link}', $b_switch_link, $main);
$main = str_replace('{title}', $b_switch_title, $main);
$main = str_replace('{b_name}', $b_name, $main);
$main = str_replace('{b_value}', $b_value, $main);

$main = str_replace('{head_title}', $head_title, $main);
$main = str_replace('{warning}', $warning, $main);


$header = file_get_contents('templates/header.html');

$header = str_replace('{header-buttons}', $headerButtons, $header); //шаблон кнопок
$header = str_replace('{logo_img}',$logo_img,$header);
$header = str_replace('{title}',$title,$header);

//Волшебная кнопка ^-^
$link_Out = "logOut.php";
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


$footer = file_get_contents('templates/footer.html');

$footer = str_replace('{designer_name}', $designer_name,$footer);
$footer = str_replace('{link_github}', $link_github,$footer);
$footer = str_replace('{img_github}', $img_github,$footer);
$footer = str_replace('{mail}', $mail,$footer);

$main = str_replace('{footer}', $footer, $main);

if(isset($_POST['register'])){
    $count = -1;
    $username = $_POST['username'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        //$main = str_replace('<div style="display: none;">{message}</div>',"Такой пользователь уже зарегистрирован",$main);
        //$content = "Такой пользователь уже зарегистрирован";
        header('Location: auth.php?state=Register_warning'.$switch_link);
    }
    else{
        $password = $_POST['password'];
        $nickname = $_POST['name'];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (nickname, username, password) VALUES (:nickname, :username, :password)");
        $stmt->bindParam(':nickname', $nickname);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
        //$warning = '<div class="success">"Регистрация прошла успешно"</div>';
        header('Location: auth.php?state=Success'.$switch_link);
    }


}

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare('SELECT id, password FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($username && password_verify($password, $user['password'])) {
        //session_start();
        $_SESSION['user_id'] = $user['id'];
        header('location: index.php');
    } else {
        //$warning = '<div class="warning">"Неверный логин или пароль"</div>';
        header('Location: auth.php?state=Login_warning'.$switch_link);
    }
}

print $main;
