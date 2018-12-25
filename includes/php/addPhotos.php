<?php
/**
 * Created by Michel Desilets
 * User: User
 * Date: 2018-09-01
 * Time: 10:07
 */

header('content-type: text/javascript');

$function = $_GET['function'];

if ($function == addFolder) {
    require_once '../../classes/database/cl_foldersDB.php';
    $type = $_GET['type'];
    $author = $_GET['author'];
    $decade = $_GET['decade'];
    $year = $_GET['year'];
    $title = $_GET['title'];
    $levels = $_GET['levels'];

    $folderData = array($type, $author, $decade, $year, $title, $levels);

    $db = new foldersDB();
    $db->addFolder($folderData);
}

if ($function == addFolderMysql) {
    require_once '../../classes/database/cl_foldersDB.php';
    $type = $_GET['type'];
    $author = $_GET['author'];
    $decade = $_GET['decade'];
    $year = $_GET['year'];
    $title = $_GET['title'];
    $levels = $_GET['levels'];

    $folderData = array($type, $author, $decade, $year, $title, $levels);

    $db = new foldersDB();
    $db->addFolderMysql($folderData);
}

if ($function === addMetadataToMysql) {
    $meta = json_decode($_GET['meta']);
    require_once '../../classes/database/cl_foldersDB.php';

    $db = new foldersDB();
    $db->addMetadataToMysql($meta);
}