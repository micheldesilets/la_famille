<?php

class photosBD
{

  /* --- GETHOMEPHOTO --- */
  public function getHomePhoto()
  {
    include 'connection/connect.php';
//    require_once 'classes/business/cl_photos.php';

    $photo = new Photos();

    $sql = "
SELECT *
FROM photos_pho pho
  JOIN parameters_par pp
  ON id_pho = pp.home_idpho_par
  JOIN repository_titles_rpt rpt
  ON rpt.id_rpt = pho.idrpt_pho
  WHERE pp.id_par = 1";

    if ($result = mysqli_query($con, $sql)) {
// Return the number of rows in result set
//$rowcount = mysqli_num_rows($result);
//printf("Result set has %d rows.\n", $rowcount);
    } else {
      echo("nothing");
    };

// Associative array
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    $photo->set_F_Path($row["full_rpt"]);
    $photo->set_Filename($row["filename_pho"]);
    $photo->set_Title($row["title_pho"]);

    mysqli_close($con);
    return $photo;
  }

  /* --- GETSIDEBARPHOTO --- */
  public function getSidebarPhoto()
  {
    // current directory
    $wd = getcwd();
    require_once 'classes/business/cl_photos.php';
    include 'connection/connect.php';

    $photoSb = new Photos();

    $sql = "
SELECT pho.filename_pho, rpt.full_rpt
FROM photos_pho pho
  JOIN parameters_par pp
  ON id_pho = pp.home_sidebar_par   
  JOIN repository_titles_rpt rpt
  ON rpt.id_rpt = pho.idrpt_pho

  WHERE pp.id_par = 1";

    if ($result = mysqli_query($con, $sql)) {
// Return the number of rows in result set
//$rowcount = mysqli_num_rows($result);
//printf("Result set has %d rows.\n", $rowcount);
    } else {
      echo("nothing");
    };

// Associative array
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    $photoSb->set_P_Path($row["full_rpt"]);
    $photoSb->set_Filename($row["filename_pho"]);

    mysqli_close($con);
    return $photoSb;
  }

  /* --- GETPHOTOS --- */
  public function getPhotos($path)
  {
    include '../connection/connect.php';
    require_once '../classes/business/cl_photos.php';

    $photo = new Photos();

    $sql = "SELECT *
  FROM repository_titles_rpt rpt
  INNER JOIN photos_pho pho
  ON rpt.id_rpt = pho.idrpt_pho
  WHERE rpt.id_rpt = $path";

    if ($result = mysqli_query($con, $sql)) {
      // Return the number of rows in result set
      $rowcount = mysqli_num_rows($result);
    } else {
      echo("nothing");
    };


    $photoArray = array();
    $l = 1;

    while ($l <= $rowcount):
      // Associative array
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

      $photo = new Photos();

      $photo->set_Title($row["title_pho"]);
      $photo->set_Keywords($row["keywords_pho"]);
      $photo->set_Height($row["height_pho"]);
      $photo->set_Width($row["width_pho"]);
      $photo->set_Caption($row["caption_pho"]);
      $photo->set_F_Path($row["full_rpt"]);
      if ($row["preview_rpt"] == null) {
        $photo->set_P_Path("");
      } else {
        $photo->set_P_Path($row["preview_rpt"]);
      }
      $photo->set_Filename($row["filename_pho"]);
      $photo->set_Pdf($row['pdf_pho']);
      array_push($photoArray, $photo);

      $l++;
    endwhile;

    // Free result set
    mysqli_free_result($result);

    mysqli_close($con);

    header("Content-Type: application/json");
    $json = json_encode($photoArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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
  }

  /* --- GETSEARCHPHOTOS --- */
  public function getSearchPhotos($kwords)
  {
    include '../connection/connect.php';
    require_once '../classes/business/cl_photos.php';

    $photo = new Photos();

    $sql = "SELECT
*
  FROM photos_pho pho
  INNER JOIN paths_pat pat
  ON pat.id_pat = pho.idpat_pho
  WHERE pho.keywords_pho LIKE '%" . $kwords[0] . "%'";
    for ($i = 1; $i < count($kwords); $i++) {
      if (!empty($kwords[$i])) {
        $sql .= " OR pho.keywords_pho LIKE '%" . $kwords[$i] . "%'";
      }
    }
    $sql = $sql . " GROUP BY pho.filename_pho";

    if ($result = mysqli_query($con, $sql)) {
      // Return the number of rows in result set
      $rowcount = mysqli_num_rows($result);
    } else {
      echo("nothing");
    };


    $photoArray = array();
    $l = 1;

    while ($l <= $rowcount):
      // Associative array
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

      $photo = new Photos();

      $photo->set_Title($row["title_pho"]);
      $photo->set_Keywords($row["keywords_pho"]);
      $photo->set_Height($row["height_pho"]);
      $photo->set_Width($row["width_pho"]);
      $photo->set_Caption($row["caption_pho"]);
      $photo->set_F_Path($row["path_pat"]);
      if ($row["prev_path_pat"] == null) {
        $photo->set_P_Path("");
      } else {
        $photo->set_P_Path($row["prev_path_pat"]);
      }
      $photo->set_Filename($row["filename_pho"]);
      $photo->set_Pdf($row['pdf_pho']);
      array_push($photoArray, $photo);

      $l++;
    endwhile;

    // Free result set
    mysqli_free_result($result);

    mysqli_close($con);

    header("Content-Type: application/json");
    $json = json_encode($photoArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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
  }

}


