<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-10-30
 * Time: 18:46
 */
include_once '../../initialize.php';
include_once PRIVATE_PHP_PATH . '/programs/CreateJson.php';
include_once PRIVATE_PHP_PATH . '/factories/json/factory/JsonClientEcho.php';

$function = $_GET['function'];

if ($function === 'getGeneologyList') {
    $worker = new JsonClientEcho(10, 2);
}
