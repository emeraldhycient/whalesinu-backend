<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include "../../controller/admin.php";

$admin = new admin();

if (isset($_GET["all"])) {
    echo $admin::paymentmethods();
}

if (isset($_POST["update"])) {
    if (
        !empty($_POST["id"]) &&  !empty($_POST["bitcoin"]) &&  !empty($_POST["ethereum"]) &&
        !empty($_POST["bnb"]) &&  !empty($_POST["instruction"])
    ) {
        echo $admin::updatepaymentmethods($_POST["id"], $_POST["bitcoin"], $_POST["ethereum"], $_POST["bnb"], $_POST["usdt"],!empty($_POST["instruction"]));
    }
}