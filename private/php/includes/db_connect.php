<?php
include_once INCLUDES_PATH . 'psl-config.php';   // As functions.php is not
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $con = $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
//    $mysqli->set_charset("utf8");
} catch(Exception $e) {
    error_log($e->getMessage());
    exit('Error connecting to database'); //Should be a message a typical user could understand
}