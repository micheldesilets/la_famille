<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-10-30
 * Time: 18:46
 */
include_once '../../initialize.php';
require_once CLASSES_PATH . '/database/cl_geneologyDB.php';

$function = $_GET['function'];

if ($function === 'getGeneologyList') {
    $db = new cl_geneologyDB();
    $db->getGeneologyList();
}
