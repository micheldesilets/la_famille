<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-20
 * Time: 13:20
 */
foreach (glob("programs/*.php") as $file) {
    include $file;
}

foreach (glob("factories/text/*.php") as $file) {
    include $file;
}

foreach (glob("factories/text/adapters/*.php") as $file) {
    include $file;
}