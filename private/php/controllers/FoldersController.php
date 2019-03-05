<?php
/**
 * Created by Michel Desilets.
 * Date: 2018-09-08
 * Time: 06:27
 */

include_once '../../initialize.php';
require_once CLASSES_PATH . '/database/cl_foldersDB.php';
require_once CLASSES_PATH . '/business/cl_folders.php';
require_once CLASSES_PATH . '/business/FolderLevels.php';
include_once PRIVATE_PHP_PATH . '/classes/CreateJson.php';

header('content-type: text/javascript');

$function = $_GET['function'];

$db = new foldersDB();

if ($function === 'getFoldersTree') {
    $db->getFoldersTree();
    exit;
}

if ($function === 'getShiftingFolders') {
    $db->getShiftingFolders();
    return;
}

if ($function === 'GetFoldersLevel1') {
    $member = $_GET['idmem'];
    $db->GetFoldersLevel1($member);
}

if ($function === 'GetFoldersLevel2') {
    $idParent = $_GET['idParent'];
    $db->GetFoldersLevel2($idParent);
}

if ($function === 'GetFoldersLevel3') {
    $idParent = $_GET['idParent'];
    $db->GetFoldersLevel3($idParent);
}

// Add a folder for storing photos
if ($function == 'addFolder') {
    /*    $type = $_GET['type'];
        $member = $_GET['member'];
        $decade = $_GET['decade'];
        $year = $_GET['year'];
        $title = $_GET['title'];
        $levels = $_GET['levels'];
        $title = carReplace($title);*/
    $level0Id = $_GET['level0Id'];
    $level0Name = $_GET['level0Name'];
    $level1Id = $_GET['level1Id'];
    $level1Name = $_GET['level1Name'];
    $level2Id = $_GET['level2Id'];
    $level2Name = $_GET['level2Name'];
    $level3Name = $_GET['level3Name'];

    $folderData = array($level0Id, $level0Name, $level1Id, $level1Name,
        $level2Id, $level2Name, $level3Name);

    $db->addFolder($folderData);
    return;
}

if ($function == 'addFolderMysql') {
    $type = $_GET['type'];
    $member = $_GET['member'];
    $decade = $_GET['decade'];
    $year = $_GET['year'];
    $title = $_GET['title'];
    $levels = $_GET['levels'];
    $title = carReplace($title);

    $folderData = array($type, $member, $decade, $year, $title, $levels);

    $db->addFolderMysql($folderData);
    return;
}

if ($function === 'getFolders') {
    $year = $_GET['year'];

    $db = new foldersDB();
    $years = $db->getFolders($year);
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



