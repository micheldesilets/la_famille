<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-09-29
 * Time: 08:17
 */
/*function createFolders($title, $levels)
{*/
$levels = 4;
$full = 'img/family/Michel/1970-1979/1975/Princeville Chantal et Micha/full';
$prev = 'img/family/Michel/1970-1979/1975/Princeville Chantal et Micha/preview';
$fullDirs = explode('/', $full);
$prevDirs = explode('/', $prev);
$dir = '';

if ($levels == 4) {
  foreach ($fullDirs as $part) {
    if (strlen($dir) == 0) {
      $dir .= '../' . $part . '/';
    } else {
      $dir .= $part . '/';
    }

//    $dir='M:\WebSites\lesnormandeaudesilets\img';
    if (!file_exists($dir)) {
      if (!is_dir($dir) && strlen($dir) > 0) {
        mkdir($dir);
      }
    }
  }

  foreach ($prevDirs as $part) {
    if (strlen($dir) == 0) {
      $dir .= '../' . $part . '/';
    } else {
      $dir .= $part . '/';
    }

//    $dir='M:\WebSites\lesnormandeaudesilets\img';
    if (!file_exists($dir)) {
      if (!is_dir($dir) && strlen($dir) > 0) {
        mkdir($dir);
      }
    }
  }


};


//}
