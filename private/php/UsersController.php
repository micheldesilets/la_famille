<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-01-17
 * Time: 13:00
 */

include_once '../../private/initialize.php';
include_once CLASSES_PATH . '/database/cl_usersDB.php';
require_once INCLUDES_PATH . 'privilegedUser.php';

$function = $_GET['function'];
$member = $_GET['member'];

try {
    if ($function === 'getMainFolder') {
        $db = new cl_usersDB();
        $db->GetMaimFolder($member);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    exit;
}
