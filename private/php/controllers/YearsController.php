<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-10-30
 * Time: 18:46
 */
include_once '../../initialize.php';
include_once CLASSES_PATH . '/business/cl_year.php';
include_once PRIVATE_PHP_PATH . '/programs/CreateJson.php';
include_once PRIVATE_PHP_PATH . '/factories/json/factory/JsonClientEcho.php';

$function = $_GET['function'];

/*if ($function === 'getYearsSelected') {
    $decade = $_GET['decade'];

    $db = new cl_yearsDB();
    $db->getYearsSelected($decade);
}*/

if ($function === 'getAllYears') {
    $worker = new JsonClientEcho(2, ''); /* Factory Method Design Pattern */
}