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
        try {
            include INCLUDES_PATH . 'db_connect.php';

            $sql = "SELECT fol.id_fol, typ.id_typ, mem.first_name_mem,
                           deca.decade_deca, yea.year_yea, fol.title_fol,
                           fol.levels_fol, mem.prefix_mem 
                      FROM folders_fol fol
                           INNER JOIN members_mem mem
                                   ON fol.idmem_fol = mem.id_mem
                           INNER JOIN decade_deca deca
                                   ON fol.iddec_fol = deca.id_deca
                           INNER JOIN year_yea yea
                                   ON fol.idyea_fol = yea.id_yea
                           INNER JOIN type_typ typ
                                   ON fol.idtyp_fol = typ.id_typ
                     WHERE typ.id_typ = ?
                  ORDER BY binary mem.first_name_mem desc, deca.decade_deca, 
                           yea.year_yea, fol.title_fol";

            $stmt = $con->prepare($sql);
            $typ = 2;
            $stmt->bind_param("i", $typ);
            $stmt->execute();
            $stmt->bind_result($idfol, $idtyp, $firstname,
                $decade, $year, $title, $levels, $prefix);

            $folderArray = array();
            while ($stmt->fetch()) {
                $folder = new folders();

                $folder->setFolderId(strval($idfol));
                $folder->setTypeId(strval($idtyp));
                $folder->setMember($prefix . $firstname);
                $folder->setDecade(strval($decade));
                $folder->setYear(strval($year));
                $folder->setTitle($title);
                $folder->setLevels(strval($levels));

                array_push($folderArray, $folder);
            }

            $stmt->close();

            $json = createJson($folderArray);
            echo $json;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }

    public function getShiftingFolders()
    {
        try {
            include INCLUDES_PATH . 'db_connect.php';

            $folder = new folders();

            $sql = "SELECT fol.id_fol, tt.type_typ, mem.first_name_mem, 
                           dd.decade_deca, yy.year_yea, fol.title_fol
                      FROM folders_fol fol
                           JOIN members_mem mem
                             ON fol.idmem_fol = mem.id_mem
                           JOIN type_typ tt
                             ON fol.idtyp_fol = tt.id_typ
                           JOIN decade_deca dd
                             ON fol.iddec_fol = dd.id_deca
                           JOIN year_yea yy
                             ON fol.idyea_fol = yy.id_yea
                  ORDER BY mem.first_name_mem, tt.type_typ, dd.decade_deca, 
                           yy.year_yea, fol.title_fol";

            $stmt = $con->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($idfol, $idtyp, $firstname,
                $decade, $year, $title);

            $folderArray = array();

            while ($stmt->fetch()) {
                $folder = new folders();

                $folder->setFolderId(strval($idfol));
                $folder->setTypeId("2");
                $folder->setMember("");
                $folder->setDecade("");
                $folder->setYear("");
                $folder->setTitle($title);
                $folder->setLevels("");

                array_push($folderArray, $folder);
            }

            $stmt->close();
            unset($conn);
            unset($stmt);

            $json = createJson($folderArray);
            echo $json;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }

    function addFolder($folderData)
    {
        include INCLUDES_PATH . 'db_connect.php';
        try {
            $type = $folderData[0];
            $member = $folderData[1];
            $decade = $folderData[2];
            $year = $folderData[3];
            $title = $folderData[4];
            $levels = $folderData[5];

            $sql = "SELECT typ.type_typ
                      FROM type_typ typ
                     WHERE typ.id_typ = ?
                           UNION ALL
                           SELECT mem.first_name_mem
                             FROM members_mem mem
                            WHERE mem.id_mem = ?
                            UNION ALL
                           SELECT decade_deca
                             FROM decade_deca deca
                            WHERE deca.id_deca = ?
                            UNION ALL
                           SELECT year_yea
                             FROM year_yea yea
                     WHERE yea.id_yea = ?";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("iiii", $type, $member, $decade, $year);
            $stmt->execute();
            $stmt->bind_result($data);

            $folderArray = [];
            while ($stmt->fetch()) {
                array_push($folderArray, $data);
            }
            array_push($folderArray, $title);
            array_push($folderArray, $levels);

            $stmt->close();
            unset($stmt);

            $this->createFolder($folderArray);
            return;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }

    function createFolder($folder)
    {
        $path = PUBLIC_PATH . '/img';

        $path = $path . "/" . $folder[0];
        if (!file_exists($path)) {
            mkdir($path);
        };

        $path = $path . '/' . $folder[1];
        if (!file_exists($path)) {
            mkdir($path);
        };

        $path = $path . '/' . $folder[2];
        if (!file_exists($path)) {
            mkdir($path);
        };

        $path = $path . '/' . $folder[3];
        if (!file_exists($path)) {
            mkdir($path);
        };

        $path = $path . '/' . $folder[4];
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
        $member = $folderData[1];
        $decade = $folderData[2];
        $year = $folderData[3];
        $title = $folderData[4];
        $levels = $folderData[5];

        try {
            $sql = "INSERT INTO folders_fol (
                                idtyp_fol, title_fol,iddec_fol, idyea_fol, 
                                levels_fol, idmem_fol)
                         VALUES (?,?,?,?,?,?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("isiiii", $typePhoto, $title, $decade, $year,
                                        $levels, $member);
            $stmt->execute();
            $stmt->close();

            $stmt = $con->prepare("  TRUNCATE TABLE photos_folders_pfo");
            $stmt->execute();
            $stmt->close();

            $sql = "INSERT INTO photos_folders_pfo (
                                full_pfo, preview_pfo, idfol_pfo, dummy_pfo)
                    SELECT CASE 
                           WHEN typ.id_typ = 1 THEN CONCAT('public/img/', 
                                typ.type_typ, '/', fol.title_fol, '/full/') 
                           WHEN typ.id_typ = 6 THEN CONCAT('public/img/', 
                                typ.type_typ, '/full/') 
                           WHEN typ.id_typ = 3 THEN CONCAT('public/img/', 
                                typ.type_typ, '/', fol.title_fol, '/') 
                           WHEN typ.id_typ = 2 
                                AND fol.levels_fol = 4 THEN CONCAT('public/img/', 
                                typ.type_typ, '/', mem.first_name_mem, '/', 
                                deca.decade_deca, '/', yea.year_yea, '/', 
                                fol.title_fol, '/full/') 
                           WHEN typ.id_typ = 2 
                                AND fol.levels_fol = 2 THEN CONCAT('public/img/', 
                                typ.type_typ, '/', mem.first_name_mem, '/', 
                                fol.title_fol, '/full/') 
                           ELSE CONCAT('public/img/', typ.type_typ, '/') 
                           END,
                           CASE 
                           WHEN typ.id_typ = 1 THEN CONCAT('public/img/', 
                                typ.type_typ, '/', fol.title_fol, '/preview/') 
                           WHEN typ.id_typ = 6 THEN CONCAT('public/img/', 
                                typ.type_typ, '/preview/') 
                           WHEN typ.id_typ = 3 THEN CONCAT('public/img/', 
                                typ.type_typ, '/', fol.title_fol, '/') 
                           WHEN typ.id_typ = 2 
                                AND fol.levels_fol = 4 THEN CONCAT('public/img/', 
                                typ.type_typ, '/', 
                                mem.first_name_mem, '/', deca.decade_deca, 
                                '/', yea.year_yea, '/', fol.title_fol, 
                                '/preview/') 
                           WHEN typ.id_typ = 2 
                                AND fol.levels_fol = 2 THEN CONCAT('public/img/', 
                                    typ.type_typ, '/', mem.first_name_mem, '/', 
                                    fol.title_fol, '/preview/') 
                           ELSE CONCAT('img/', typ.type_typ, '/') 
                           END,
                           fol.id_fol,
                           fol.levels_fol
                      FROM folders_fol fol
                           JOIN type_typ typ
                             ON fol.idtyp_fol = typ.id_typ
                           JOIN members_mem mem
                             ON fol.idmem_fol = mem.id_mem
                           JOIN decade_deca deca
                             ON fol.iddec_fol = deca.id_deca
                           JOIN year_yea yea
                             ON fol.idyea_fol = yea.id_yea
                     WHERE fol.id_fol = fol.id_fol";
            $stmt = $con->prepare("$sql");
            $stmt->execute();
            $stmt->close();

        } catch (Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }

    function getFolders($year)
    {
        include INCLUDES_PATH . 'db_connect.php';
        try {
            $sql = "SELECT id_fol, title_fol
                      FROM folders_fol fol
                           JOIN year_yea yea
                             ON fol.idyea_fol = yea.id_yea
                     WHERE yea.id_yea = ?";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $year);
            $stmt->bind_result($idfol, $title);
            $stmt->execute();

            $folderArray = [];
            while ($stmt->fetch()) {
                $folder = new folders();

                $folder->setFolderId($idfol);
                $folder->setTitle($title);

                array_push($folderArray, $folder);
            }

            header("Content-Type:application/json");
            $json = createJson($folderArray);
            echo $json;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }

    function getPath($path)
    {
        $typePhoto = $path[0];
        $member = $path[1];
        $decade = $path[2];
        $year = $path[3];
        $title = $path[4];

        include INCLUDES_PATH . 'db_connect.php';
        try {
            $sql = "SELECT fol.id_fol, typ.type_typ, mem.first_name_mem,
                           deca.decade_deca, yea.year_yea, fol.title_fol
                      FROM type_typ typ, members_mem mem, decade_deca deca,
                           year_yea yea, folders_fol fol
                     WHERE typ.id_typ = ?
                       AND mem.id_mem = ?
                       AND deca.id_deca = ? 
                       AND yea.id_yea = ?
                       AND fol.id_fol = ?";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("iiiii", $typePhoto, $member , $decade, $year,
                $title);
            $stmt->bind_result($idfol, $type, $firstname, $decade, $year, $title);
            $stmt->execute();

            $stmt->fetch();

            $path = PUBLIC_PATH . '/img/' . utf8_encode($type) . '/' .
                utf8_encode($firstname) . '/' . $decade . '/' .
                strval($year) . '/' . utf8_encode($title) . '/';
            $info = [$path, $idfol];
            return $info;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit();
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