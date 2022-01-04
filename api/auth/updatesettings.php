<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once "../../controller/auth.php";

$auth = new Auth();

if (!empty($_POST["userid"])) {
    echo $auth::updatesettings($_POST["userid"], $_POST["fullname"], $_POST["email"]);
}