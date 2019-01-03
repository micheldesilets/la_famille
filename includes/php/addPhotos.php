<?php
/**
 * Created by Michel Desilets
 * User: User
 * Date: 2018-09-01
 * Time: 10:07
 */
include_once '../../private/initialize.php';

header('content-type: text/javascript');

$function = $_GET['function'];
require_once CLASSES_PATH . '/database/cl_foldersDB.php';

if ($function == addFolder) {
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

    $db = new foldersDB();
    $db->addMetadataToMysql($meta);
}