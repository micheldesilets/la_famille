<?php
include_once '../../private/initialize.php';

header('content-type: text/javascript');

$function = $_GET['function'];
require_once CLASSES_PATH . '/database/cl_PhotosDB.php';

if ($function == 'getInfo') {
    $pid = $_GET['pid'];
    $db = new photosBD();
    $db->getInfoPhoto($pid);
}

if ($function == 'insertPhotoInfo') {
    $photoId = $_GET['photoId'];
    $title = $_GET['title'];
    $keywords = $_GET['keyWords'];
    $caption = $_GET['caption'];
    $year = $_GET['year'];
    $geneologyIdxs = $_GET['geneologyIdxs'];

    $infoData = array($photoId, $title, $keywords, $caption, $year, $geneologyIdxs);

    $db = new photosBD();
    $db->insertPhotoInfo($infoData);
}

