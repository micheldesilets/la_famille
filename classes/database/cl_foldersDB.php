<?php

/**
 * Created by Michel Desilets.
 * Date: 2018-10-19
 * Time: 10:04
 */
class foldersDB
{
    public function getFoldersTree()
    {
        include '../connection/connect.php';
        require_once '../classes/business/cl_folders.php';

        $folder = new folders();

        $sql = "SELECT rpt.id_rpt,typ.id_typ,aut.first_name_aut,deca.decade_deca,
                   yea.year_yea,rpt.title_rpt,rpt.levels_rpt,aut.prefix_aut
            FROM folders_fol rpt
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

            $folder = new folders();

            $folder->setFolderId($row["id_rpt"]);
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

    function addFolder($folderData)
    {
        // current directory
        $wd = getcwd();
        require_once '../classes/business/cl_folders.php';
        include '../connection/connect.php';

        $type = $folderData[0];
        $author = $folderData[1];
        $decade = $folderData[2];
        $year = $folderData[3];
        $title = $folderData[4];
        $levels = $folderData[5];

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

        $folderArray = array(mysqli_fetch_array($result, MYSQLI_ASSOC), mysqli_fetch_array($result, MYSQLI_ASSOC),
            mysqli_fetch_array($result, MYSQLI_ASSOC), mysqli_fetch_array($result, MYSQLI_ASSOC), $title, $levels);

        mysqli_close($con);

        $this->createFolder($folderArray);

        return;
    }

    function createFolder($folder)
    {
        chdir('../');
        $curr = getcwd();

        $typePhoto = $folder[0];
        $author = $folder[1];
        $decade = $folder[2];
        $year = $folder[3];
        $title = $folder[4];

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

    function addFolderMysql($folderData)
    {
        $curr = getcwd();
        include '../connection/connect.php';

        $typePhoto = $folderData[0];
        $author = $folderData[1];
        $decade = $folderData[2];
        $year = $folderData[3];
        $title = $folderData[4];
        $levels = $folderData[5];

        $sql = "CALL addFolder($typePhoto,'" . $title . "',$author,$decade,$year,$levels)";

        if (mysqli_query($con, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . " < br>" . mysqli_error($con);
        }

        mysqli_close($con);
    }

    function getFolders($year)
    {
        $wd = getcwd();

        require_once '../classes/business/cl_folders.php';
        include '../connection/connect.php';

        $sql = "CALL getFolders($year)";

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

            $folder = new folders();

            $folder->setFolderId($row['id_rpt']);
            $folder->setTitle($row["title_rpt"]);

            array_push($folderArray, $folder);

            $l++;
        endwhile;

        // Free result set
        mysqli_free_result($result);

        mysqli_close($con);

        $json = createJson($folderArray);
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