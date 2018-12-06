<?php
/**
 * Created by Michel Desilets
 * User: User
 * Date: 2018-09-01
 * Time: 10:07
 */

header('content-type: text/javascript');

$function = $_GET['function'];

if ($function == addRepository) {
    require_once '../classes/database/cl_repositoriesDB.php';
    $type = $_GET['type'];
    $author = $_GET['author'];
    $decade = $_GET['decade'];
    $year = $_GET['year'];
    $title = $_GET['title'];
    $levels = $_GET['levels'];

    $repositData = array($type, $author, $decade, $year, $title, $levels);

    $db = new repository();
    $db->addRepository($repositData);
}

if ($function == addRepositoryMysql) {
    require_once '../classes/database/cl_repositoriesDB.php';
    $type = $_GET['type'];
    $author = $_GET['author'];
    $decade = $_GET['decade'];
    $year = $_GET['year'];
    $title = $_GET['title'];
    $levels = $_GET['levels'];

    $repositData = array($type, $author, $decade, $year, $title, $levels);

    $db = new repository();
    $db->addRepositoryMysql($repositData);
}

if ($function === addMetadataToMysql) {
    $meta = json_decode($_GET['meta']);
    require_once '../classes/database/cl_repositoriesDB.php';

    $db = new repository();
    $db->addMetadataToMysql($meta);
}