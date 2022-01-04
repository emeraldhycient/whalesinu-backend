<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once "../../controller/coin.php";
include_once "../../config/config.php";

if (isset($_GET['coin_id'])) {
    echo coins::mark_active($_GET['coin_id']);
}