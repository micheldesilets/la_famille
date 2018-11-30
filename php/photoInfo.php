<?php
header('content-type: text/javascript');

$function = $_GET['function'];
$pid = $_GET['pid'];
/*
require_once '../classes/database/cl_getPhotosDB.php';
$db = new photosBD();
$info = $db->getInfoPhoto($pid);*/




if ($function == 'getInfo') {
    require_once '../classes/database/cl_getPhotosDB.php';

    $db = new photosBD();
    $db->getInfoPhoto($pid);
}