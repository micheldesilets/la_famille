<?php
/**
 * Created by Michel Desilets
 * Date: 2018-09-08
 * Time: 07:49
 */

class foldersDB
{
  /*** Get Folder Data ***/
  public function getRepositories()
  {
    include '../connection/connect.php';
    require_once '../classes/business/cl_folders.php';

    $folder = new Folders();

    $sql = "SELECT rpt.id_rpt,typ.id_typ,cre.first_name_cre,deca.decade_deca,
                   yea.year_yea,rpt.title_rpt
            FROM repository_titles_rpt rpt
                 INNER JOIN creator_cre cre
                 ON rpt.idcre_rpt = cre.id_cre
                 INNER JOIN decade_deca deca
                 ON rpt.iddec_rpt = deca.id_deca
                 INNER JOIN year_yea yea
                 ON rpt.idyea_rpt = yea.id_yea
                 INNER JOIN type_typ typ
                 ON rpt.idtyp_rpt = typ.id_typ
             WHERE yea.year_yea <> 0
             ORDER BY typ.id_typ, cre.first_name_cre, deca.decade_deca, yea.year_yea";

    if ($result = mysqli_query($con, $sql)) {
      // Return the number of rows in result set
      $rowcount = mysqli_num_rows($result);
    } else {
      echo("nothing");
    };


    $folderArray = array();
    $l = 1;

    while ($l <= $rowcount):
      // Associative array
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

      $folder = new Folders();

      $folder->setRepositoryId($row["id_rpt"]);
      $folder->setTypeId($row["id_typ"]);
      $folder->setCreator($row["first_name_cre"]);
      $folder->setDecade($row["decade_deca"]);
      $folder->setYear($row["year_yea"]);
      $folder->setTitle($row["title_rpt"]);

      array_push($folderArray, $folder);

      $l++;
    endwhile;

    // Free result set
    mysqli_free_result($result);

    mysqli_close($con);

    header("Content-Type: application/json");
    $json = json_encode($folderArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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
