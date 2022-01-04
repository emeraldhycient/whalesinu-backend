<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once "../../controller/coin.php";
include_once "../../config/config.php";

if (
    !empty($_POST['coin_name']) && !empty($_POST['price']) && !empty($_POST['bnb']) && !empty($_POST['btc'])
    && !empty($_POST['eth'] && !empty($_POST['qty']) && !empty($_POST['purchased']) && !empty($_POST['endingon']))
) {
    echo coins::create_ico(
        $_POST['coin_name'],
        $_POST['qty'],
        $_POST['price'],
        $_POST['bnb'],
        $_POST['btc'],
        $_POST['eth'],
        $_POST['endingon'],
        $_POST['purchased']
    );
}