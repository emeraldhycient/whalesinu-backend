<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include "../../controller/user.php";

$user = new users();

if (
    !empty($_POST["userid"]) && !empty($_POST["qty"]) && !empty($_POST["coin_id"])
    && !empty($_POST["currency"]) && !empty($_POST["price"] && $_POST["wallet"])
) {
    echo  $user::DepositRequest(
        $_POST["userid"],
        $_POST["coin_id"],
        $_POST["qty"],
        $_POST["price"],
        $_POST["currency"],
        $_POST["wallet"],
    );
}