<?php
/**
 * Created by Michel Desilets
 * Date: 2018-08-23
 * Time: 11:38
 */
include_once '../../initialize.php';
include_once PHP_PATH . '/classes/GetObjects.php';
include_once CLASSES_PATH . '/business/cl_objects.php';
include_once PHP_PATH . '/classes/CreateJson.php';

$path = $_GET['path'];

$db = new GetObjects($path);
$db->getObjects();
