<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include "../../controller/user.php";

$user = new users();

if (
    !empty($_POST["fullname"])  && !empty($_POST["password"]) && !empty($_POST["email"])
) {
    echo $user::createaccount(
        $_POST["fullname"],
        $_POST["password"],
        $_POST["email"],
        $_POST["isadmin"]
    );
}