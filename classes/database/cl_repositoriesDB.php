<?php

/**
 * Created by Michel Desilets.
 * Date: 2018-10-19
 * Time: 10:04
 */
class repository
{
    public function getRepositories()
    {
        include '../connection/connect.php';
        require_once '../classes/business/cl_repositories.php';

        $folder = new Folders();

        $sql = "SELECT rpt.id_rpt,typ.id_typ,aut.first_name_aut,deca.decade_deca,
                   yea.year_yea,rpt.title_rpt,rpt.levels_rpt,aut.prefix_aut
            FROM repository_titles_rpt rpt
                 INNER JOIN author_aut aut
                 ON rpt.idaut_rpt = aut.id_aut
                 INNER JOIN decade_deca deca
                 ON rpt.iddec_rpt = deca.id_deca
                 INNER JOIN year_yea yea
                 ON rpt.idyea_rpt = yea.id_yea
                 INNER JOIN type_typ typ
                 ON rpt.idtyp_rpt = typ.id_typ
             WHERE typ.id_typ = 2
             ORDER BY typ.id_typ, aut.first_name_aut, deca.decade_deca, yea.year_yea";

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
            $folder->setAuthor($row["prefix_aut"] . $row["first_name_aut"]);
            $folder->setDecade($row["decade_deca"]);
            $folder->setYear($row["year_yea"]);
            $folder->setTitle($row["title_rpt"]);
            $folder->setLevels($row["levels_rpt"]);

            array_push($folderArray, $folder);

            $l++;
        endwhile;

        // Free result set
        mysqli_free_result($result);

        mysqli_close($con);

        $json = createJson($folderArray);
        echo $json;
    }

    function addRepository($repositData)
    {
        // current directory
        $wd = getcwd();
        require_once '../classes/business/cl_repositories.php';
        include '../connection/connect.php';

        $type = $repositData[0];
        $author = $repositData[1];
        $decade = $repositData[2];
        $year = $repositData[3];
        $title = $repositData[4];
        $levels = $repositData[5];

        $repositArray = array();
        $repository = new Folders();

//        $sql= 'CALL getRepositoryDescriptions($type,$author,$decade,$year)';

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

        $repositArray = [mysqli_fetch_array($result, MYSQLI_ASSOC), mysqli_fetch_array($result, MYSQLI_ASSOC),
            mysqli_fetch_array($result, MYSQLI_ASSOC), mysqli_fetch_array($result, MYSQLI_ASSOC), $title, $levels];

        mysqli_close($con);

        $this->createRepositoryFolder($repositArray);

        return;
    }

    function createRepositoryFolder($repository)
    {
        chdir('../');
        $curr = getcwd();

        $typePhoto = $repository[0];
        $author = $repository[1];
        $decade = $repository[2];
        $year = $repository[3];
        $title = $repository[4];

        $path = $curr . '/assets/img';

        $path = $path . "/" . $typePhoto[type_typ];
        if (!file_exists($path)) {
            mkdir($path);
        };

        $path = $path . '/' . $author[type_typ];
        if (!file_exists($path)) {
            mkdir($path);
        };

        $path = $path . '/' . $decade[type_typ];
        if (!file_exists($path)) {
            mkdir($path);
        };

        $path = $path . '/' . $year[type_typ];
        if (!file_exists($path)) {
            mkdir($path);
        };

        $path = $path . '/' . $title;
        if (!file_exists($path)) {
            mkdir($path);
            chdir($path);
            mkdir('full');
            mkdir('preview');
        } else {
            echo 'Le répertoire existe dans la base de données.';
        };
    }

    function addRepositoryMysql($repositData)
    {
        $curr = getcwd();
        include '../connection/connect.php';

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
        /*
        $cars = array("Volvo", "BMW", "Toyota");
        $json=createJson($cars);
        echo $json;*/
    }

    function getReposits($year)
    {
        $wd = getcwd();

        require_once '../classes/business/cl_repositories.php';
        include '../connection/connect.php';

        $sql = "CALL getRepositories($year)";

        if ($result = mysqli_query($con, $sql)) {
            // Return the number of rows in result set
            $rowcount = mysqli_num_rows($result);
        } else {
            echo("nothing");
        };

        $repositArray = array();
        $l = 1;

        while ($l <= $rowcount):
            // Associative array
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            $reposit = new Folders();

            $reposit->setRepositoryId($row['id_rpt']);
            $reposit->setTitle($row["title_rpt"]);

            array_push($repositArray, $reposit);

            $l++;
        endwhile;

        // Free result set
        mysqli_free_result($result);

        mysqli_close($con);

        $json = createJson($repositArray);
        echo $json;
    }

    function addMetadataToMysql($idRpt, $file_name)
    {
        $curr = getcwd();
        include '../connection/connect.php';

        $sql = "CALL addPhotoToDB($idRpt,'" . utf8_encode($file_name) . "')";

        if (mysqli_query($con, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . " < br>" . mysqli_error($con);
        }

        mysqli_close($con);
        return;
    }

    function getPath($path)
    {
        $typePhoto = $path[0];
        $author = $path[1];
        $decade = $path[2];
        $year = $path[3];
        $title = $path[4];

        include '../connection/connect.php';

        $sql = "CALL getPath($typePhoto,$author,$decade,$year,$title)";

        if ($result = mysqli_query($con, $sql)) {
            // Return the number of rows in result set
            $rowcount = mysqli_num_rows($result);
        } else {
            echo("nothing");
        };

        IF ($rowcount > 0) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $current = getcwd() . "\n";
            $path = '../assets/img/' . utf8_encode($row['type_typ']) . '/' . utf8_encode($row['first_name_aut']) . '/' . $row['decade_deca'] . '/' .
                $row['year_yea'] . '/' . utf8_encode($row['title_rpt']) . '/';
            $info = [$path, $row['id_rpt']];
            return $info;
        }
    }
}

function createJson($rawData)
{
    header("Content-Type: application/json");
    $json = json_encode($rawData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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