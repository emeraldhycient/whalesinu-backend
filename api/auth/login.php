<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once "../../controller/auth.php";

$auth = new Auth();

if (!empty($_POST['email']) && !empty($_POST['password'])) {
    echo $auth::Login($_POST['email'], $_POST['password']);
}