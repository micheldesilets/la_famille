<?php
/**
 * Created by Michel Desilets.
 * Date: 2018-09-08
 * Time: 06:27
 */

header('content-type: text/javascript');

$function = $_GET['function'];

require_once '../classes/database/cl_foldersDB.php';
$db = new foldersDB();

if ($function === 'getFolders') {
    $db->getFoldersTree();
    return;
}

if ($function === 'getShiftingFolders') {
    $db->getShiftingFolders();
    return;
}

if ($function == addFolder) {
//    require_once '../classes/database/cl_foldersDB.php';
    $type = $_GET['type'];
    $author = $_GET['author'];
    $decade = $_GET['decade'];
    $year = $_GET['year'];
    $title = $_GET['title'];
    $levels = $_GET['levels'];
    $title = carReplace($title);

    $folderData = array($type, $author, $decade, $year, $title, $levels, $title);

//    $db = new foldersDB();
    $db->addFolder($folderData);
    return;
}

if ($function == addFolderMysql) {
//    require_once '../classes/database/cl_foldersDB.php';
    $type = $_GET['type'];
    $author = $_GET['author'];
    $decade = $_GET['decade'];
    $year = $_GET['year'];
    $title = $_GET['title'];
    $levels = $_GET['levels'];
    $title = carReplace($title);

    $folderData = array($type, $author, $decade, $year, $title, $levels);

//    $db = new foldersDB();
    $db->addFolderMysql($folderData);
    return;
}

if ($function === addMetadataToMysql) {
    $meta = json_decode($_GET['meta']);
//    require_once '../classes/database/cl_foldersDB.php';

//    $db = new foldersDB();
    $db->addMetadataToMysql($meta);
    return;
}

function carReplace($folderName)
{
    $folderName = str_replace(' ', '_', $folderName);
    $folderName = str_replace('é', 'e', $folderName);
    $folderName = str_replace('è', 'e', $folderName);
    $folderName = str_replace('ê', 'e', $folderName);
    $folderName = str_replace('ä', 'a', $folderName);
    $folderName = str_replace('û', 'u', $folderName);
    $folderName = str_replace('à', 'a', $folderName);
    $folderName = str_replace('É', 'E', $folderName);
    $folderName = str_replace('ô', 'o', $folderName);
    $folderName = str_replace('ç', 'c', $folderName);

    return $folderName;
}



