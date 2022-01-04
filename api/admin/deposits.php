<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include "../../controller/admin.php";

$admin = new admin();

/*
if (isset($_GET["unprocessed"])) {
    echo $admin::unprocesseddeposit();
}*/

echo $admin::Deposits();

/*if (isset($_POST["update"])) {
    if (!empty($_POST["status"]) && !empty($_POST["userid"])) {
        echo $admin::processdeposit($_POST["status"], $_POST["userid"], $_POST["amount"]);
    }
}*/