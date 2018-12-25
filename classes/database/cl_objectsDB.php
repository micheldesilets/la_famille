<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-21
 * Time: 10:54
 */

class objectsDB
{
  /* Get readings
  ***************/
  public function getObjects($path)
  {
    include_once '../../connection/connect.php';
    require_once '../../classes/business/cl_objects.php';

    $sql = "SELECT * FROM objects_obj obj
  JOIN photos_folders_pfo pfo
  ON pfo.idfol_pfo = obj.idfol_obj
  WHERE pfo.idfol_pfo = $path
    ORDER BY order_obj";

    if ($result = mysqli_query($con, $sql)) {
      // Return the number of rows in result set
      $rowcount = mysqli_num_rows($result);
      /* printf("Result set has %d rows.\n", $rowcount); */
    } else {
      echo("nothing");
    };


    $objectArray = array();
    $l = 1;

    while ($l <= $rowcount):
      // Associative array
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

      $object = new Objects();

      $object->set_description($row["description_obj"]);
        $object->set_File($row['preview_pfo'] . $row['file_obj']);
      array_push($objectArray, $object);

      $l++;
    endwhile;

    // Free result set
    mysqli_free_result($result);

    mysqli_close($con);

    header("Content-Type: application/json");
    $json = json_encode($objectArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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
