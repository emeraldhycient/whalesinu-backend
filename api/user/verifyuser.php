<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once "../../controller/user.php";
include_once "../../config/config.php";

$user = new users;

if($_GET["token"]){
  echo  $user::verifyuser($_GET["token"]);
}else{
    echo "no token found or invalid url";
}
$user::verifycurrentuser();
