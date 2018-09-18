<?php

class photosBD
{
  /* --- GETSIDEBARPHOTO --- */
  public function getSidebarPhoto()
  {
    // current directory
    $wd = getcwd();
    require_once 'classes/business/cl_photos.php';
    include 'connection/connect.php';

    $photoSb = new Photos();

    $sql = "SELECT pho.filename_pho, pfo.full_pfo
            FROM photos_pho pho
            JOIN parameters_par pp
            ON id_pho = pp.home_sidebar_par   
            JOIN photosFolders_pfo pfo
           ON pfo.idrpt_pfo = pho.idrpt_pho
           WHERE pp.id_par = 1";

    if ($result = mysqli_query($con, $sql)) {
    } else {
      echo("nothing");
    };

// Associative array
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    $photoSb->set_P_Path($row["full_pfo"]);
    $photoSb->set_Filename($row["filename_pho"]);

    mysqli_close($con);
    return $photoSb;
  }

  /* --- GETPHOTOS --- */
  public function getPhotos($path)
  {
    $sql = "SELECT *
            FROM photosFolders_pfo pfo
            INNER JOIN photos_pho pho
            ON pfo.idrpt_pfo = pho.idrpt_pho
            WHERE pfo.idrpt_pfo = $path";

    $json = $this->createJason($sql);
    echo $json;
  }

  /* --- GETSEARCHPHOTOS --- */
  public function getSearchPhotos($kwords)
  {
    $sql = "SELECT *
            FROM photos_pho pho
            INNER JOIN photosFolders_pfo pfo
            ON pfo.idrpt_pfo = pho.idrpt_pho
            JOIN repository_titles_rpt rpt 
            ON pfo.idrpt_pfo = rpt.id_rpt
            WHERE (pho.keywords_pho LIKE '%" . $kwords[0] . "%'";
    for ($i = 1; $i < count($kwords); $i++) {
      if (!empty($kwords[$i])) {
        $sql .= " OR pho.keywords_pho LIKE '%" . $kwords[$i] . "%'";
      }
    }
    for ($i = 0; $i < count($kwords); $i++) {
      if (!empty($kwords[$i])) {
        $sql .= " OR pho.caption_pho LIKE '%" . $kwords[$i] . "%'";
      }
    }
    $sql = $sql . ") AND (rpt.idtyp_rpt = 1 OR rpt.idtyp_rpt = 2) GROUP BY pho.filename_pho";

    $json = $this->createJason($sql);
    echo $json;
  }

  private function createJason($sql)
  {
    include '../connection/connect.php';
    require_once '../classes/business/cl_photos.php';

    $photo = new Photos();

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

      if ($row["title_pho"] == null) {
        $photo->set_Title("");
      } else {
        $photo->set_Title($row["title_pho"]);
      }
      if ($row["keywords_pho"] == null) {
        $photo->set_Keywords("");
      } else {
        $photo->set_Keywords($row["keywords_pho"]);
      }
      $photo->set_Height($row["height_pho"]);
      $photo->set_Width($row["width_pho"]);
      if ($row["caption_pho"] == null) {
        $photo->set_Caption("");
      } else {
        $photo->set_Caption($row["caption_pho"]);
      }
      $photo->set_F_Path($row["full_pfo"]);
      if ($row["preview_pfo"] == null) {
        $photo->set_P_Path("");
      } else {
        $photo->set_P_Path($row["preview_pfo"]);
      }
      $photo->set_Filename($row["filename_pho"]);
      if ($row["pdf_pho"] == null) {
        $photo->set_Pdf("");
      } else {
        $photo->set_Pdf($row['pdf_pho']);
      }

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
    return $json;
  }
}

