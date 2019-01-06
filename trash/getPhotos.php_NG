<?php
include_once '../../private/initialize.php';
header('content-type: text/javascript');

$path = $_GET['path']; /* Répertoire des photos */

require_once CLASSES_PATH . '/database/cl_PhotosDB.php';
$db = new photosBD();
$photo = $db->getPhotos($path);



