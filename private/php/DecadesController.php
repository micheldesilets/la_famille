<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-10-30
 * Time: 18:46
 */
include_once '../../private/initialize.php';
require_once CLASSES_PATH . '/database/cl_yearsDB.php';

$function = $_GET['function'];

if ($function === 'getDecades') {
    $db = new cl_yearsDB();
    $db->getDecades();
}