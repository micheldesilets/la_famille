<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-10-22
 * Time: 19:46
 */

class repositoryMysql
{
  function addRepositoryToMysql($repositData)
  {
    $curr = getcwd();
    include '../connection/connect.php';
    /*
        $con = mysqli_connect("localhost", "mdesilets", "ehf4EaQ_CU(N", "lesnormandeaudesilets");
        if (mysqli_connect_errno()) {
          echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }*/

    $typePhoto = $repositData[0];
    $author = $repositData[1];
    $decade = $repositData[2];
    $year = $repositData[3];
    $title = $repositData[4];
    $levels = $repositData[5];

    $sql = "CALL addRepository($typePhoto,'" . $title . "',$author,$decade,$year,$levels)";

    if (mysqli_query($con, $sql)) {
      echo "New record created successfully";
    } else {
      echo "Error: " . $sql . " < br>" . mysqli_error($con);
    }

    mysqli_close($con);
    return;
  }
}
