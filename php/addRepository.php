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
    $title = carReplace($title);

    $repositData = array($type, $author, $decade, $year, $title, $levels,$title);

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
    $title = carReplace($title);

    $repositData = array($type, $author, $decade, $year, $title, $levels);

    $db = new repository();
    $db->addRepositoryMysql($repositData);
}

if ($function === addMetadataToMysql) {
    $meta = json_decode($_GET['meta']);
    require_once '../classes/database/cl_repositoriesDB.php';

    $db = new repository();
    $db->addMetadataToMysql($meta);
    return;
}

function carReplace($repositName)
{
    $repositName = str_replace(' ', '_', $repositName);
    $repositName = str_replace('é', 'e', $repositName);
    $repositName = str_replace('è', 'e', $repositName);
    $repositName = str_replace('ê', 'e', $repositName);
    $repositName = str_replace('ä', 'a', $repositName);
    $repositName = str_replace('û', 'u', $repositName);
    $repositName = str_replace('à', 'a', $repositName);
    $repositName = str_replace('É', 'E', $repositName);
    $repositName = str_replace('ô', 'o', $repositName);
    $repositName = str_replace('ç', 'c', $repositName);

    return $repositName;
}