<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-01-17
 * Time: 13:00
 */

include_once '../../private/initialize.php';
include_once CLASSES_PATH . '/database/cl_authorsDB.php';

$function = $_GET['function'];
try {
    if ($function === 'getAuthors') {
        $db = new cl_authorsDB();
        $db->GetAuthors();
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    exit;
}
