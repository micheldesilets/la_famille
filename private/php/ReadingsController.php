
<?php
/**
 * Created by Michel Desilets
 * Date: 2018-08-23
 * Time: 11:38
 */
include_once '../../private/initialize.php';
require_once CLASSES_PATH . '/database/cl_readingsDB.php';
require_once CLASSES_PATH . '/business/cl_readings.php';
include_once CLASSES_PATH . '/createJson.php';

$path = $_GET['path']; /* Reading path  */


$db = new readingsDB();
$db->getReadings($path);
