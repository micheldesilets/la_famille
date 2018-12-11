<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-12-10
 * Time: 11:57
 */

// File and rotation
$currpwd = getcwd();
$thumb = $_GET['thumb'];
$full =  $_GET['full'];
$degrees = $_GET['direction'];

$filename = $thumb;
$filename='../' . $filename;
//$degrees = -90;

// Content type
header('Content-type: image/jpeg');

// Load
$source = imagecreatefromjpeg($filename);

// Rotate
$rotate = imagerotate($source, $degrees,0);

// Output
imagejpeg($rotate,$filename);

// Free the memory
imagedestroy($source);
imagedestroy($rotate);

//////////////////////////////////////////////

$filename = $full;
$filename='../' . $filename;

// Content type
header('Content-type: image/jpeg');

// Load
$source = imagecreatefromjpeg($filename);

// Rotate
$rotate = imagerotate($source, $degrees,0);

// Output
imagejpeg($rotate,$filename);

// Free the memory
imagedestroy($source);
imagedestroy($rotate);
