<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once "../../controller/coin.php";
include_once "../../config/config.php";

echo coins::current_ico();