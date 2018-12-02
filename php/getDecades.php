<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-10-30
 * Time: 18:46
 */
require_once '../classes/database/cl_yearsDB.php';

$db = new cl_yearsDB();
$db->getDecades();