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
  public function getReadings($path)
  {
    include_once '../connection/connect.php';
    require_once '../classes/business/cl_readings.php';

//    $lectures = new Readings();

    $sql = "SELECT * FROM readings_rea
  JOIN paths_pat pat
  ON pat.id_pat = idpat_rea
  WHERE pat.id_pat = $path
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
      if (!empty($row["intro_rea"])) {
        $reading->set_Intro($row["intro_rea"]);
      }
      if (!empty($row['summary_rea'])) {
        $reading->set_sumary($row['summary_rea']);
      }
      $reading->set_File($row[path_pat] . $row['file_rea']);
      array_push($readingArray, $reading);

      $l++;
    endwhile;

    // Free result set
    mysqli_free_result($result);

    mysqli_close($con);

    header("Content-Type: application/json");
    $json = json_encode($readingArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
      // Avoid echo of empty string (which is invalid JSON), and
      // JSONify the error message instead:
      $json = json_encode(array("jsonError", json_last_error_msg()));
      if ($json === false) {
        // This should not happen, but we go all the way now:
        $json = '{"jsonError": "unknown"}';
      }
      // Set HTTP response status code to: 500 - Internal Server Error
      http_response_code(500);
    }
    echo $json;
//    echo count($photoArray);


//    return $readingArray;

  }
}
