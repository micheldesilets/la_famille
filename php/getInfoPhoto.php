<?php
header('content-type: text/javascript');

$pid = $_GET['pid'];
require_once '../classes/database/cl_getPhotosDB.php';
$db = new photosBD();
$info = $db->getInfoPhoto($pid);
