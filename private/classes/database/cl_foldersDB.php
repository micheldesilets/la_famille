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
        include INCLUDES_PATH . 'db_connect.php';

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
        include INCLUDES_PATH . 'db_connect.php';

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
        include INCLUDES_PATH . 'db_connect.php';

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
        include INCLUDES_PATH . 'db_connect.php';
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $typePhoto = $folderData[0];
        $author = $folderData[1];
        $decade = $folderData[2];
        $year = $folderData[3];
        $title = $folderData[4];
        $levels = $folderData[5];

        try {
            $stmt = $con->prepare("INSERT INTO folders_fol (idtyp_fol, title_fol, 
                                   idaut_fol, iddec_fol, idyea_fol, levels_fol)
                    VALUES (?,?,?,?,?,?)");
                $stmt->bind_param("isiiii", $typePhoto, $title, $author,
                    $decade, $year, $levels);
            $stmt->execute();
            $stmt->close();

            $stmt = $con->prepare("  TRUNCATE TABLE photos_folders_pfo");
            $stmt->execute();
            $stmt->close();

            $sql="INSERT INTO photos_folders_pfo (full_pfo, preview_pfo, 
                              idfol_pfo, dummy_pfo)
                  SELECT
                      CASE WHEN typ.id_typ = 1 
                               THEN CONCAT('public/img/', 
                                typ.type_typ, '/', fol.title_fol, '/full/') 
                           WHEN typ.id_typ = 6 
                               THEN CONCAT('public/img/', typ.type_typ, '/full/') 
                           WHEN typ.id_typ = 3 
                               THEN CONCAT('public/img/', typ.type_typ, '/', 
                                    fol.title_fol, '/') 
                           WHEN typ.id_typ = 2 AND fol.levels_fol = 4 
                               THEN CONCAT('public/img/', typ.type_typ, '/', 
                                    aut.first_name_aut, '/', deca.decade_deca, 
                                    '/', yea.year_yea, '/', fol.title_fol, 
                                    '/full/') 
                           WHEN typ.id_typ = 2 AND fol.levels_fol = 2 
                               THEN CONCAT('public/img/', typ.type_typ, '/', 
                                    aut.first_name_aut, '/', fol.title_fol, 
                                    '/full/') ELSE CONCAT('public/img/', 
                                    typ.type_typ, '/') 
                           END,
                      CASE WHEN typ.id_typ = 1 
                               THEN CONCAT('public/img/', typ.type_typ, '/', 
                                    fol.title_fol, '/preview/') 
                           WHEN typ.id_typ = 6 
                               THEN CONCAT('public/img/', typ.type_typ, 
                                    '/preview/') 
                           WHEN typ.id_typ = 3 
                               THEN CONCAT('public/img/', typ.type_typ, '/', 
                                    fol.title_fol, '/') 
                           WHEN typ.id_typ = 2 AND fol.levels_fol = 4 
                               THEN CONCAT('public/img/', typ.type_typ, '/', 
                                    aut.first_name_aut, '/', deca.decade_deca, 
                                    '/', yea.year_yea, '/', fol.title_fol, 
                                    '/preview/') 
                           WHEN typ.id_typ = 2 AND fol.levels_fol = 2 
                               THEN CONCAT('public/img/', typ.type_typ, '/', 
                                    aut.first_name_aut, '/', fol.title_fol, 
                                    '/preview/') ELSE CONCAT('img/', 
                                    typ.type_typ, '/') 
                           END,
                           fol.id_fol,
                           fol.levels_fol
                      FROM folders_fol fol
                          JOIN type_typ typ
                              ON fol.idtyp_fol = typ.id_typ
                          JOIN author_aut aut
                              ON fol.idaut_fol = aut.id_aut
                          JOIN decade_deca deca
                              ON fol.iddec_fol = deca.id_deca
                          JOIN year_yea yea
                              ON fol.idyea_fol = yea.id_yea
                      WHERE fol.id_fol = fol.id_fol";
            $stmt=$con->prepare("$sql");
            $stmt->execute();
            $stmt->close();

        } catch (Exception $e) {
            error_log($e->getMessage());
            exit(); //Should be a message a typical user could understand
        }
    }

    function getFolders($year)
    {
        $wd = getcwd();
        include INCLUDES_PATH . 'db_connect.php';

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

    function getPath($path)
    {
        $typePhoto = $path[0];
        $author = $path[1];
        $decade = $path[2];
        $year = $path[3];
        $title = $path[4];

        include INCLUDES_PATH . 'db_connect.php';

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