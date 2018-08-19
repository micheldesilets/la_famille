<?php

header('content-type: text/javascript');

$path = $_GET['path']; /* Répertoire des photos */

require_once 'getPhotosDB.php';
$db = new photosBD();
$photo = $db->getPhotos($path);


