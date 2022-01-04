<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include "../../controller/admin.php";

$admin = new admin();

if (!empty($_POST["userid"]) && !empty($_POST["tx_ref"]) && !empty($_POST["amount"])) {
   echo  $admin::processdeposit($_POST["tx_ref"], $_POST["userid"], $_POST["amount"], $_POST["coinid"]);
}