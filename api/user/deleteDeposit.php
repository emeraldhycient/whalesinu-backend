<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include "../../controller/user.php";

$user = new users();

if (
    !empty($_GET["tx_ref"])
) {
    echo  $user::deleteRequest($_GET["tx_ref"]);
}