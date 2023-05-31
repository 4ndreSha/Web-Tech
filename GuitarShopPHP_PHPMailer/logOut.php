<?php

session_start();
unset($_SESSION['user_id']);
session_destroy();
if(isset($_GET['path'])) {
    header('Location: ' . $_GET['path']);
}
else {
    header('Location: index.php');
}

exit();