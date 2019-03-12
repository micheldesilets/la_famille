<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-08
 * Time: 13:40
 */

spl_autoload_register(function ($class) {
    $filename = __DIR__ . '/' . $class . '.php';
    $filename = str_replace('\\', '/', $filename);
    include_once $filename;
});