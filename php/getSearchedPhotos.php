<?php
/**
 * Created by Michel Desilets
 * User: User
 * Date: 2018-09-01
 * Time: 10:07
 */
header('content-type: text/javascript');

$kwords = $_GET['kwrd']; /* Keywords */
$startYear = $_GET['startYear'];
$endYear = $_GET['endYear'];
$wExact = $_GET['wExact'];
$wPart = $_GET['wPart'];
$searchKw = $_GET['searchKw'];
$searchTitles = $_GET['searchTitles'];
$searchComments = $_GET['searchComments'];
$photoPid = $_GET['photoId'];
$idUnique = $_GET['idUnique'];
$idContext = $_GET['idContext'];

$kwArr = explode(',', $kwords);
array_walk($kwArr, trimValue);

$searchData = array($kwords, $startYear, $endYear, $wExact, $wPart, $searchKw, $searchTitles, $searchComments, $photoPid, $idUnique, $idContext);

require_once '../classes/database/cl_getPhotosDB.php';
$db = new photosBD();
$photo = $db->getSearchPhotos($searchData);

function trimValue(&$value)
{
  $value = trim($value);
}

;
