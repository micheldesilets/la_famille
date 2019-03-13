<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-10-30
 * Time: 18:46
 */
include_once '../../initialize.php';
include_once PROJECT_PATH . '/Autoload.php';

use priv\php\factories\json\factory as factory;

$function = $_GET['function'];

if ($function === 'getGeneologyList') {
    $worker = new factory\JsonClientEcho(10, 2);
}
