<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once "../../controller/admin.php";
include_once "../../config/config.php";

$auth = new admin();

if (isset($_GET['userid'])) {
    echo $auth::userdetails($_GET['userid'], $_GET['userid']);
}