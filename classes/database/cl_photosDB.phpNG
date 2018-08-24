<?php

class photosBD
{

  /* --- GETHOMEPHOTO --- */
  public function getHomePhoto()
  {
    include 'connection/connect.php';
    require_once 'classes/business/cl_photos.php';

    $photo = new Photos();

    $sql = "
SELECT *
FROM photos_pho
  JOIN parameters_par pp
  ON id_pho = pp.home_idpho_par
  JOIN paths_pat pp1
  ON pp1.id_pat = idpat_pho
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

    $photo->set_F_Path($row["path_pat"]);
    $photo->set_Filename($row["filename_pho"]);
    $photo->set_Title($row["title_pho"]);

    mysqli_close($con);
    return $photo;
  }

  /* --- GETSIDEBARPHOTO --- */
  public function getSidebarPhoto()
  {
    include 'connection/connect.php';

    $photoSb = new Photos();

    $sql = "
SELECT pho.filename_pho, pp1.prev_path_pat
FROM photos_pho pho
  JOIN parameters_par pp
  ON id_pho = pp.home_sidebar_par
  JOIN paths_pat pp1
  ON pp1.id_pat = idpat_pho
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

    $photoSb->set_P_Path($row["prev_path_pat"]);
    $photoSb->set_Filename($row["filename_pho"]);

    mysqli_close($con);
    return $photoSb;
  }

  /* --- GETPHOTOS --- */
  public function getPhotos($path)
  {
    include 'connection/connect.php';
    require_once 'classes/business/cl_photos.php';

    $photo = new Photos();

    $sql = "SELECT
*
FROM paths_pat
  INNER JOIN photos_pho
    ON paths_pat.id_pat = photos_pho.idpat_pho
WHERE paths_pat.id_pat = $path";

    if ($result = mysqli_query($con, $sql)) {
      // Return the number of rows in result set
      $rowcount = mysqli_num_rows($result);
      /* printf("Result set has %d rows.\n", $rowcount); */
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
      $photo->set_P_Path($row["prev_path_pat"]);
      $photo->set_Filename($row["filename_pho"]);
      $photo->set_Pdf($row['pdf_pho']);
      array_push($photoArray, $photo);

      $l++;
    endwhile;

    // Free result set
    mysqli_free_result($result);

    mysqli_close($con);

//    echo json_encode($photoArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    return $photoArray;
  }

  /* --- DISPLAYPHOTOS --- */
  public function displayPhotos($result)
  {

  }
}



