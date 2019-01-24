<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-01-17
 * Time: 13:00
 */

include_once '../../private/initialize.php';
include_once CLASSES_PATH . '/database/cl_usersDB.php';
require_once INCLUDES_PATH . 'PrivilegedUser.php';

$function = $_GET['function'];
$user = $_GET['user'];

try {
    if ($function === 'getUsers') {
        $db = new cl_usersDB();
        $db->GetUsers($user);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    exit;
}
