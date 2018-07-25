<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-21
 * Time: 10:54
 */

class readingsDB
{
  /* Get readings */
  public function getReadings()
  {
    include_once 'connection/connect.php';
    require_once 'classes/business/cl_readings.php';

//    $lectures = new Readings();

    $sql = "SELECT * FROM readings_rea
  JOIN paths_pat pp1
  ON pp1.id_pat = idpat_rea
  ORDER BY order_rea";

    if ($result = mysqli_query($con, $sql)) {
      // Return the number of rows in result set
      $rowcount = mysqli_num_rows($result);
      /* printf("Result set has %d rows.\n", $rowcount); */
    } else {
      echo("nothing");
    };


    $readingArray = array();
    $l = 1;

    while ($l <= $rowcount):
      // Associative array
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

      $reading = new Readings();

      $reading->set_Title($row["title_rea"]);
      $reading->set_Address($row["address_rea"]);
      $reading->set_Resume($row['resume_rea']);
      $reading->set_File($row[path_pat] . '/' . $row['file_rea']);
      array_push($readingArray, $reading);

      $l++;
    endwhile;

    // Free result set
    mysqli_free_result($result);

    mysqli_close($con);
//    echo count($photoArray);


    return $readingArray;

  }
}
