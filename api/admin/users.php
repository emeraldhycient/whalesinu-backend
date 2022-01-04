<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include "../../controller/admin.php";

$admin = new admin();

echo $admin::users();