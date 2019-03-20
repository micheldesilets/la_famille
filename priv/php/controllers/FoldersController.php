<?php
/**
 * Created by Michel Desilets.
 * Date: 2018-09-08
 * Time: 06:27
 */

include_once '../../initialize.php';
include_once PROJECT_PATH . '/Autoload.php';

use priv\php\{factories\json\factory as factory,
    classes\database as database,
    programs as prog};

header('content-type: text/javascript');

$function = $_GET['function'];

$db = new database\FoldersDB();

if ($function === 'getFoldersTree') {
   $jsonTree = new factory\JsonClientReturn(15, '');
    $tree = new prog\BuildFolderTree($jsonTree->getJsonFactory());
    $tree->buildTree();
    echo $tree->getHtmlString();

 // $worker = new factory\JsonClientEcho(8, 2);
    exit;
}

if ($function === 'getShiftingFolders') {
    $worker = new factory\JsonClientEcho(9, '');
    return;
}

if ($function === 'GetFoldersLevelOne') {
    $member = $_GET['idmem'];
    $worker = new factory\JsonClientEcho(12, $member);
}

if ($function === 'GetFoldersLevelTwo') {
    $idParent = $_GET['idParent'];
    $worker = new factory\JsonClientEcho(13, $idParent);
}

if ($function === 'GetFoldersLevelThree') {
    $idParent = $_GET['idParent'];
    $worker = new factory\JsonClientEcho(14, $idParent);
}

// Add a folder for storing photos
if ($function == 'addFolderToSite') {
    $level0Id = $_GET['level0Id'];
    $level0Name = $_GET['level0Name'];
    $level1Id = $_GET['level1Id'];
    $level1Name = $_GET['level1Name'];
    $level2Id = $_GET['level2Id'];
    $level2Name = $_GET['level2Name'];
    $level3Name = $_GET['level3Name'];

    $folderData = array($level0Id, $level0Name, $level1Id, $level1Name,
        $level2Id, $level2Name, $level3Name);

    $worker = new prog\AddFolderToSite($folderData);
    $worker->addFolder();

    $worker = new prog\AddFolderToMysql($folderData);
    $worker->addFolder();
    return;
}

/*if ($function == 'addFolderMysql') {
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
}*/

/*if ($function === 'getFolders') {
    $year = $_GET['year'];

    $db = new database\FoldersDB();
    $years = $db->getFolders($year);
    return;
}*/

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



