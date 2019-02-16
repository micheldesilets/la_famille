<?php
/**
 * Created by PhpStorm.
 * User: Michel Desilets
 * Date: 2018-12-21
 * Time: 11:33
 */

include_once '../../private/initialize.php';
include_once CLASSES_PATH . '/returnJson.php';
include_once CLASSES_PATH . '/database/cl_PhotosDB.php';
include_once CLASSES_PATH . '/business/cl_photos.php';
include_once INCLUDES_PATH . 'functions.php';
include_once INCLUDES_PATH . "role.php";
include_once INCLUDES_PATH . "privilegedUser.php";

include_once CLASSES_PATH . '/createJson.php';
//include_once CLASSES_PATH . '/returnJson.php';

if (isset($_POST['function'])) {
    $function = $_POST['function'];
} else {
    $function = $_GET['function'];
}

$db = new photosDB();

if ($function === 'zipAndDownload') {
    if (isset($_POST['pids'])) {
        $listPids = json_decode($_POST['pids']);
        $photos = $db->downloadPhotos($listPids);
        $listPhotos = json_decode($photos, true);

// Checking files are selected
        $curr = getcwd();

        $zip = new ZipArchive();
        $zip_name =
            'public/archives/lesnormandeaudesilets' . time() . ".zip";
        $fname = $zip_name;

        if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) {
            // Opening zip file to load files
            $error .= "* Sorry ZIP creation failed at this time";
        }

        foreach ($listPhotos as $key => $value) {
            $path = $value["path"];
            $file = $value["filename"];
            $filename = $path . $file;
            $zip->addFile($filename);
        }

        $zip->close();

        if (file_exists($zip_name)) {
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header('Content-type: application/zip');
            header("Content-Disposition: attachment; filename=$zip_name");
            header("Content-Transfer-Encoding: binary");
            readfile($zip_name);
        }
    }
}

if ($function === 'removeZipFile') {
    $curr = getcwd();
    chdir('../../');
    $dir = "public/archives";
    $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
    $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($ri as $file) {
        $file->isDir() ? rmdir($file) : unlink($file);
    }

    $dir = "photos_Normandeau_Desilets";
    $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
    $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($ri as $file) {
        $file->isDir() ? rmdir($file) : unlink($file);
    }
    return true;
}

if ($function === 'getPhotos') {
    $path = $_GET['path']; /* Répertoire des photos */
    $db = new photosDB();
    $db->getPhotos($path);
}

if ($function == 'getInfo') {
    $pid = $_GET['pid'];
    $db = new photosDB();
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

    $db = new photosDB();
    $db->insertPhotoInfo($infoData);
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

    require_once CLASSES_PATH . '/database/cl_PhotosDB.php';
    $db = new photosDB();
    $db->getSearchPhotos($searchData);
    return;
}

if ($function === 'deletePhotos') {
    if (isset($_POST['pids'])) {
        $listPids = json_decode($_POST['pids']);
        $db->deletePhotos($listPids);
    }
}

function trimValue(&$value)
{
    $value = trim($value);
}