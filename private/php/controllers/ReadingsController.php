
<?php
/**
 * Created by Michel Desilets
 * Date: 2018-08-23
 * Time: 11:38
 */
include_once '../../initialize.php';
require_once PHP_PATH . '/classes/GetReadings.php';
require_once CLASSES_PATH . '/business/cl_readings.php';
include_once PHP_PATH . '/classes/CreateJson.php';

$path = $_GET['path']; /* Reading path  */

$db = new GetReadings($path);
$db->getReadings();
