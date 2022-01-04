<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once "../../controller/auth.php";

$auth = new Auth();

if (!empty($_POST["userid"])) {
    echo $auth::addWallet($_POST["userid"], $_POST["wallet"]);
}