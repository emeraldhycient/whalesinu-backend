<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include "../../models/user.php";

$user = new users();
if(!empty($_POST["userid"])){
    echo $user::totalDeposit($_POST["userid"]);
}