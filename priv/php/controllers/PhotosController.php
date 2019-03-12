<?php
/**
 * Created by PhpStorm.
 * User: Michel Desilets
 * Date: 2018-12-21
 * Time: 11:33
 */

include_once '../../initialize.php';
include_once INCLUDES_PATH . 'functions.php';
include_once INCLUDES_PATH . "role.php";
include_once INCLUDES_PATH . "privilegedUser.php";

include_once PROJECT_PATH . '/Autoload.php';

use priv\php\factories\json\factory as factory;
use priv\php\programs as prog;
use priv\php\factories\json\products as prod;

if (isset($_POST['function'])) {
    $function = $_POST['function'];
} else {
    $function = $_GET['function'];
}

if ($function === 'zipAndDownload') {
    if (isset($_POST['pids'])) {
        $listPids = json_decode($_POST['pids']);
        $photos = new factory\JsonClientReturn(5, $listPids);

        $listPhotos = json_decode($photos->getJsonFactory(), true);
        $zip = new prog\CreateZipFile($listPhotos);
    }
}

if ($function === 'removeZipFile') {
    $remove = new prog\RemoveZipFile();
}

if ($function === 'getPhotos') {
    $path = $_GET['path'];
    $worker = new factory\JsonClientEcho(1, $path); /* Factory Method Design
 Pattern */
}

if ($function == 'getMetadata') {
    $pid = $_GET['pid'];
    $worker = new factory\JsonClientEcho(6, $pid);
 //   echo $worker->getEcho();
}

if ($function == 'insertPhotoInfo') {
    $photoId = $_GET['photoId'];
    $title = $_GET['title'];
    $keywords = $_GET['keyWords'];
    $caption = $_GET['caption'];
    $year = $_GET['year'];
    $geneologyIdxs = $_GET['geneologyIdxs'];

    $infoData = array($photoId, $title, $keywords, $caption, $year, $geneologyIdxs);

    $db = new prog\UpdatePhotoMetadata($infoData);
}

if ($function === 'getSearchPhotos') {
    $kwords = $_GET['kwrd']; /* Keywords */
    $startYear = $_GET['startYear'];
    $endYear = $_GET['endYear'];
    $wExact = $_GET['wExact'];
    $wPart = $_GET['wPart'];
    $searchKw = $_GET['searchKw'];
    $searchTitles = $_GET['searchTitles'];
    $searchComments = $_GET['searchComments'];
    $photoPid = $_GET['photoId'];
    $idUnique = $_GET['idUnique'];
    $idContext = $_GET['idContext'];

    if ($kwords != "nothingness") {
        $kwArr = explode(',', $kwords);
        array_walk($kwArr, 'trimValue');
    }

    if ($photoPid == "nothing") {
        $photoPid = "";
    }

    if ($startYear == "start") {
        $startYear = '1900';
    }

    if ($endYear == 'end') {
        $endYear = '2050';
    }

    $searchData = array($kwArr, $startYear, $endYear, $wExact, $wPart, $searchKw, $searchTitles, $searchComments, $photoPid, $idUnique, $idContext);

    $db = new prod\GetSearchedPhotosProduct($searchData);
    echo $db->getJsonString();
    return;
}

if ($function === 'deletePhotos') {
    include_once PRIVATE_PHP_PATH . '/programs/DeletePhotosFromFolder.php';
    if (isset($_POST['pids'])) {
        $listPids = json_decode($_POST['pids']);
        $db = new DeletePhotosFromFolder($listPids);
    }
}

function trimValue(&$value)
{
    $value = trim($value);
}