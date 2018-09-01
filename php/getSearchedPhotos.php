<?php
/**
 * Created by Michel Desilets
 * User: User
 * Date: 2018-09-01
 * Time: 10:07
 */
header('content-type: text/javascript');

$kwords = $_GET['kwrd']; /* Keywords */
$kwArr = explode(',', $kwords);
array_walk($kwArr, trimValue);

require_once '../classes/database/cl_getPhotosDB.php';
$db = new photosBD();
$photo = $db->getSearchPhotos($kwArr);

function trimValue(&$value)
{
  $value = trim($value);
}
