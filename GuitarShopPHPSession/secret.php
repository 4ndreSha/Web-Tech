<?php
session_start();
if(!isset($_SESSION['user_id'])){
    $main = file_get_contents('errors/403.html');
    http_response_code(403);
}
else {
    $main = file_get_contents('templates/secret.html');
}

print $main;