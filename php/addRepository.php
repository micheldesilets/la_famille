<?php
/**
 * Created by Michel Desilets
 * User: User
 * Date: 2018-09-01
 * Time: 10:07
 */
header('content-type: text/javascript');
require_once '../classes/database/cl_repositoryDB.php';

$function = $_GET['function'];

if ($function == addRepository) {
  $type = $_GET['type'];
  $author = $_GET['author'];
  $decade = $_GET['decade'];
  $year = $_GET['year'];
  $title = $_GET['title'];

  $repositData = array($type, $author, $decade, $year, $title);

  $db = new repository();
  $repository = $db->addRepository($repositData);
}

if ($function == getYearsSelected) {
  $decade = $_GET['decade'];

//  require_once '../classes/database/cl_repositoryDB.php';
  $db = new repository();
  $repository = $db->getYearsSelected($decade);
}

