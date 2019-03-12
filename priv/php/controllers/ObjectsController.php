<?php
/**
 * Created by Michel Desilets
 * Date: 2018-08-23
 * Time: 11:38
 */
include_once '../../initialize.php';
include_once PROJECT_PATH . '/Autoload.php';

use priv\php\factories\json\factory as factory;

$path = $_GET['path'];

$worker = new factory\JsonClientEcho(3, $path); /* Factory Method Design Pattern */
