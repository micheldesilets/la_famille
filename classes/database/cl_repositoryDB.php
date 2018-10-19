<?php

/**
 * Created by Michel Desilets.
 * Date: 2018-10-19
 * Time: 10:04
 */
class repository
{

  function addRepository($repositData)
  {
    // current directory
    $wd = getcwd();
    require_once '../classes/business/cl_repository.php';
    include '../connection/connect.php';

    $type = $repositData[0];
    $author = $repositData[1];
    $decade = $repositData[2];
    $year = $repositData[3];
    $title = $repositData[4];

    $repository = new cl_repository();

    $sql = "SELECT typ.type_typ 
            FROM type_typ typ 
            WHERE typ.id_typ = $type
            UNION ALL
            SELECT aut.first_name_aut 
            FROM author_aut aut 
            WHERE aut.id_aut = $author
            UNION ALL 
            SELECT decade_deca
            FROM decade_deca deca
            WHERE deca.id_deca = $decade
            UNION ALL 
            SELECT year_yea
            FROM year_yea yea
            WHERE yea.id_yea = $year";

    if ($result = mysqli_query($con, $sql)) {
    } else {
      echo("nothing");
    };

    // Associative array
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    $repository->set_Type($row["type_typ"]);
    $repository->set_Author($row["author_aut"]);
    $repository->set_Decade($row["decade_deca"]);
    $repository->set_Year($row["year_yea"]);

    mysqli_close($con);
    return $repository;

  }

}

