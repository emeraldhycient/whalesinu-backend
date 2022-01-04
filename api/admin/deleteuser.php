<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include "../../controller/admin.php";

$admin = new admin();

if (isset($_GET["userid"])) {
    echo $admin::deleteuser($_GET["userid"]);
}