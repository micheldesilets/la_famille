<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-01-17
 * Time: 13:00
 */

include_once '../../initialize.php';
require_once INCLUDES_PATH . 'privilegedUser.php';
include_once PRIVATE_PHP_PATH . '/factories/json/factory/JsonClientEcho.php';
include_once PRIVATE_PHP_PATH . '/classes/business/cl_users.php';

$function = $_GET['function'];
$member = $_GET['member'];

try {
    if ($function === 'getMainFolder') {
        $worker = new JsonClientEcho(7, $member); /* Factory Method Design
 Pattern */
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    exit;
}
