<?php
header('content-type: text/javascript');

$function = $_GET['function'];

if ($function == 'getInfo') {
    $pid = $_GET['pid'];
    require_once '../../classes/database/cl_PhotosDB.php';

    $db = new photosBD();
    $db->getInfoPhoto($pid);
}

if ($function == 'insertPhotoInfo') {
    /*title=' + inputs.title + '&keyWords=' + inputs.keyWords +
        '&caption=' + inputs.caption + '&year=' + inputs.year + '&geneology=' + inputs.geneologyIdxs +*/
    $photoId = $_GET['photoId'];
    $title = $_GET['title'];
    $keywords = $_GET['keyWords'];
    $caption = $_GET['caption'];
    $year = $_GET['year'];
    $geneologyIdxs = $_GET['geneologyIdxs'];

    $infoData = array($photoId, $title, $keywords, $caption, $year, $geneologyIdxs);

    require_once '../../classes/database/cl_PhotosDB.php';

    $db = new photosBD();
    $db->insertPhotoInfo($infoData);
}

