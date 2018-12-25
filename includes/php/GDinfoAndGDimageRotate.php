<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-10-21
 * Time: 15:08
 */

/*$gdInfoArray = gd_info();
$version = $gdInfoArray["GD Version"];
echo $version;
echo "<hr/>";
foreach ($gdInfoArray as $key => $value) {
    echo "$key | $value<br/>";
}*/


$img = imagecreatefromjpeg('M:\WebSites\lesnormandeaudesilets_DEV\test.jpg');
$imgRotated = imagerotate($img,45,0);
imagejpeg($imgRotated,"testRotated.jpg",100);

?>

<img src="../test.jpg"/><img src="../trash/testRotated.jpg"/>
