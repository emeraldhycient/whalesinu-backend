<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include "../../controller/user.php";

$user = new users();

if (
    !empty($_POST["userid"])
) {
    echo $user::updateuser(
        $_POST["userid"],
        $_POST["fullname"],
        $_POST["password"],
        $_POST["email"],
        $_POST["act_bal"],
        $_POST["wallet"],
        $_POST["isadmin"]
    );
}