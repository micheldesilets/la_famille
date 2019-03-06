<?php
/**
 * Created by Michel Desilets
 * Date: 2018-08-23
 * Time: 11:38
 */
include_once '../../initialize.php';
include_once CLASSES_PATH . '/business/cl_objects.php';
include_once PRIVATE_PHP_PATH . '/programs/CreateJson.php';
include_once PRIVATE_PHP_PATH . '/factories/json/factory/JsonClientEcho.php';

$path = $_GET['path'];

$worker = new JsonClientEcho(3, $path); /* Factory Method Design Pattern */
