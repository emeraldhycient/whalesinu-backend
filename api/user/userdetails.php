<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once "../../controller/auth.php";
include_once "../../config/config.php";

$auth = new Auth();

if (!empty($_GET['userid'])) {
    echo $auth::userdetails($_GET['userid']);
}