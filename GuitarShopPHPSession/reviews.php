<?php
session_start();
//header("Location: index.php", true, http_response_code(503));
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

//Волшебная кнопка ^-^
$link_Out = "logOut.php?path=reviews.php";
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


//Тут начинается магия...
$tableName = 'reviews';

//$file_path = 'reviews.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(isset($_SESSION['user_id'])) {
        if(isset($_POST['new']))
        {
            if(isset($_GET['state']) && isset($_GET['id'])) {
                $sth = $pdo->prepare('SELECT user_id FROM reviews WHERE id = :id');
                $sth->bindParam('id', $_GET['id']);
                $sth->execute();
                $reviewData = $sth->fetch();
                if($_SESSION['user_id'] == $reviewData['user_id']) {
                    
                    $sql = "UPDATE reviews SET r_text = :r_text, r_rank = :r_rank WHERE id = :id";
                    $data = [
                        'id' => $_GET['id'],
                        'r_text' => $_POST['message'],
                        'r_rank' => $_POST['rank'],
                    ];
                    $stmt= $pdo->prepare($sql);
                    $stmt->execute($data);
                }
                header("Location: reviews.php");
            }
            else {
                $user_id = $_SESSION['user_id'];
                $message = $_POST['message'];
                $rank = $_POST['rank'];
        
                $regex = "/(https?:\/\/(?!www\.bsuir\.by)(?!bsuir\.by)[\w\/:%#\$&\?\(\)~\.=\+\-]+)/i";
                $message = preg_replace($regex, "#Внешние ссылки запрещены#", $message);
                
                //Загоняем комментарий в таблицу в БД
                $data = [
                    'user_id' => $user_id,
                    'r_text' => $message,
                    'r_rank' => $rank,
                ];
                $sql = "INSERT INTO reviews (user_id, r_text, r_rank) VALUES (:user_id, :r_text, :r_rank)";
                $stmt= $pdo->prepare($sql);
                $stmt->execute($data);
                
                //http://mysite.by https://www.mysite.by/price http://bsuir.by https://www.bsuir.by/ru/kafedry-bguir http://example.com
            }
        }
        if(isset($_POST['edit'])) {
            header("Location: reviews.php?state=edit&id=" . $_POST['id']);
        }
        if(isset($_POST['delete'])){
            $sql = "DELETE FROM reviews WHERE id = :id";
            $stmt= $pdo->prepare($sql);
            $stmt->bindParam(':id', $_POST['id']);
            $stmt->execute();
        }
    }
    else{
        header('Location: auth.php');
    }
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
        if(isset($_GET['state']) && $_GET['id'] == $value['id'] && $value['user_id'] == $_SESSION['user_id']) {
            $main= str_replace('{edit_text}', $value['r_text'], $main);
            $main= str_replace('{edit_rank}', $value['r_rank'], $main);
            continue;
        }
        $sth = $pdo->prepare('SELECT nickname FROM users WHERE id = :id');
        $sth->bindParam('id', $value['user_id']);
        $sth->execute();
        $userData = $sth->fetch();

        $reviewItem = file_get_contents('templates/review-item.html');
        $reviewItem = str_replace('{name}', $userData['nickname'], $reviewItem);
        $reviewItem = str_replace('{text}', $value['r_text'], $reviewItem);
        $reviewItem = str_replace('{rank}', $value['r_rank'], $reviewItem);
        if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $value['user_id'])
        {
            $reviewButtons = file_get_contents('templates/review-buttons.html');
            $reviewButtons = str_replace('{id}', $value['id'], $reviewButtons);
            $reviewItem = str_replace('{buttons}', $reviewButtons, $reviewItem);
        }
        $reviewItem = str_replace('{buttons}', "", $reviewItem);
        $allReviews .= $reviewItem;
    }

    $main= str_replace('{review-item}', $allReviews, $main);
}
else
{
    $main= str_replace('{review-item}', "Здесь еще пока нет отзывов...", $main);
}
$main= str_replace('{edit_text}', '', $main);
$main= str_replace('{edit_rank}', '', $main);



print $main;