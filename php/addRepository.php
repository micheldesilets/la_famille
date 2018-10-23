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
  require_once '../classes/database/cl_repositoryDB.php';
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

if ($function == getYearsSelected) {
  require_once '../classes/database/cl_repositoryDB.php';
  $decade = $_GET['decade'];

  $db = new repository();
  $db->getYearsSelected($decade);
}

