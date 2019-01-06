<?php
/**
 * Created by Michel Desilets
 * Date: 2018-08-23
 * Time: 11:38
 */
include_once '../../private/initialize.php';
header('content-type: text/javascript');

$path = $_GET['path']; /* Reading path  */

require_once CLASSES_PATH . '/database/cl_objectsDB.php';
$db = new objectsDB();
$reading = $db->getObjects($path);
