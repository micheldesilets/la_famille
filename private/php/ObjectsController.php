<?php
/**
 * Created by Michel Desilets
 * Date: 2018-08-23
 * Time: 11:38
 */
include_once '../../private/initialize.php';
include_once CLASSES_PATH . '/database/cl_objectsDB.php';
include_once CLASSES_PATH . '/business/cl_objects.php';
include_once CLASSES_PATH . '/createJson.php';

$path = $_GET['path'];

$db = new objectsDB();
$db->getObjects($path);
