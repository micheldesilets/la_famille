<?php

/**
 * Created by Michel Desilets.
 * Date: 2018-10-19
 * Time: 10:04
 */

require_once CLASSES_PATH . '/business/cl_folders.php';

class foldersDB
{
    public function getFoldersTree()
    {
        include CONNECTION_PATH . '/connect.php';

        $folder = new folders();
        $sql = "CALL getFoldersTree()";

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

            $folder->setFolderId($row["id_fol"]);
            $folder->setTypeId($row["id_typ"]);
            $folder->setAuthor($row["prefix_aut"] . $row["first_name_aut"]);
            $folder->setDecade($row["decade_deca"]);
            $folder->setYear($row["year_yea"]);
            $folder->setTitle($row["title_fol"]);
            $folder->setLevels($row["levels_fol"]);

            array_push($folderArray, $folder);

            $l++;
        endwhile;

        // Free result set
        mysqli_free_result($result);

        mysqli_close($con);

        $json = createJson($folderArray);
        echo $json;
    }

    public function getShiftingFolders()
    {
        include CONNECTION_PATH . '/connect.php';

        $folder = new folders();
        $sql = "CALL getShiftingFolders()";

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

            $folder->setFolderId($row["id_fol"]);
            $folder->setTypeId("2");
            $folder->setAuthor("");
            $folder->setDecade("");
            $folder->setYear("");
            $folder->setTitle($row['title_fol']);
            $folder->setLevels("");

            array_push($folderArray, $folder);

            $l++;
        endwhile;

        // Free result set
        mysqli_free_result($result);

        mysqli_close($con);
        unset($conn);

        unset($stmt);
        $json = createJson($folderArray);
        echo $json;
    }

    function addFolder($folderData)
    {
        // current directory
        $wd = getcwd();
        include CONNECTION_PATH . '/connect.php';

        $type = $folderData[0];
        $author = $folderData[1];
        $decade = $folderData[2];
        $year = $folderData[3];
        $title = $folderData[4];
        $levels = $folderData[5];

        $sql = "CALL getFolderDescriptions($type,$author,$decade,$year)";

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
        chdir('../../');
        $curr = getcwd();

        $typePhoto = $folder[0];
        $author = $folder[1];
        $decade = $folder[2];
        $year = $folder[3];
        $title = $folder[4];

        $path = $curr . '/public/img';

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
        include CONNECTION_PATH . '/connect.php';

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
        include CONNECTION_PATH . '/connect.php';

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

            $folder->setFolderId($row['id_fol']);
            $folder->setTitle($row["title_fol"]);

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
        include CONNECTION_PATH . '/connect.php';

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

        include CONNECTION_PATH . '/connect.php';

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
            $path = PUBLIC_PATH . '/img/' . utf8_encode($row['type_typ']) . '/' .
                utf8_encode($row['first_name_aut']) . '/' . $row['decade_deca'] . '/' .
                $row['year_yea'] . '/' . utf8_encode($row['title_fol']) . '/';
            $info = [$path, $row['id_fol']];
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