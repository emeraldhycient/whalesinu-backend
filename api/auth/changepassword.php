<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include "../../controller/auth.php";

$auth = new Auth();

if (
    !empty($_POST["userid"])  && !empty($_POST["oldpassword"]) && !empty($_POST["newpassword"])
) {
    echo $auth::Changepassword(
        $_POST["userid"],
        $_POST["oldpassword"],
        $_POST["newpassword"],
        $_POST["email"]
    );
}