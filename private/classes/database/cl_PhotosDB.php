<?php

require_once CLASSES_PATH . '/business/cl_photos.php';

class photosDB
{
    /* --- GETSIDEBARPHOTO --- */
    public function getSidebarPhoto()
    {
        include INCLUDES_PATH . 'db_connect.php';
        try {
            $photoSb = new Photos();

            $stmt = $con->prepare("SELECT pho.filename_pho, pfo.full_pfo
                                   FROM photos_pho pho
                                       JOIN parameters_par pp
                                       ON id_pho = pp.home_sidebar_par   
                                       JOIN photos_folders_pfo pfo
                                       ON pfo.idfol_pfo = pho.idfol_pho
                                   WHERE pp.id_par = ?");

            $i = 1;
            $stmt->bind_param("i", $i);
            $stmt->execute();
            $data = $stmt->get_result()->fetch_all();
            $stmt->close();

            $sidebar = $data[0];
            $photoSb->set_P_Path($sidebar[1]);
            $photoSb->set_Filename($sidebar[0]);

            return $photoSb;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit(); //Should be a message a typical user could understand
        }
    }

    /* --- GETPHOTOS --- */
    public function getPhotos($path)
    {
        include INCLUDES_PATH . 'db_connect.php';
        try {
            $sql = "SELECT id_pho, title_pho, keywords_pho, caption_pho, full_pfo, 
                       preview_pfo, filename_pho, pdf_pho, idgen_pho,
                       title_fol, year_pho
                FROM photos_folders_pfo pfo
                    INNER JOIN photos_pho pho
                        ON pfo.idfol_pfo = pho.idfol_pho
                    JOIN folders_fol rpt
                        ON pfo.idfol_pfo = rpt.id_fol
                WHERE pfo.idfol_pfo = ?
                ORDER BY pho.year_pho";

            $stmt = $con->prepare($sql);
            $i = "i";
            $stmt->bind_param($i, $path);
            $stmt->execute();
            $stmt->bind_result($id, $titlePho, $keywords, $caption,
                $full, $preview, $filename, $pdf, $idgen, $titleFol,
                $year);

            $listPhotos = [];
            while ($stmt->fetch()) {
                $photo = $this->setToClass($id, $titlePho, $keywords, $caption,
                    $full, $preview, $filename, $pdf, $idgen, $titleFol,
                    $year);
                array_push($listPhotos, $photo);
            }

            $stmt->close();

            header("Content-Type:application/json");
            $json = json_encode($listPhotos);
            echo $json;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit(); //Should be a message a typical user could understand
        }
    }

    /* --- GETINFOPHOTOS --- */
    public function getInfoPhoto($pid)
    {
        try {
            include INCLUDES_PATH . 'db_connect.php';

            $sql = "SELECT  id_pho, title_pho, keywords_pho, caption_pho, full_pfo, 
                       preview_pfo, filename_pho, pdf_pho, idgen_pho,
                       title_fol, year_pho
                FROM photos_pho pho
                    JOIN photos_folders_pfo pfo
                        ON pfo.idfol_pfo = pho.idfol_pho
                    JOIN folders_fol rpt
                        ON pfo.idfol_pfo = rpt.id_fol
                WHERE pho.id_pho = ?";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $pid);
            $stmt->execute();
            $stmt->bind_result($id, $titlePho, $keywords, $caption,
                $full, $preview, $filename, $pdf, $idgen, $titleFol,
                $year);

            $listPhotos = [];
            $stmt->fetch();
            $photo = $this->setToClass($id, $titlePho, $keywords, $caption,
                $full, $preview, $filename, $pdf, $idgen, $titleFol,
                $year);
            array_push($listPhotos, $photo);

            $stmt->close();

            header("Content-Type:application/json");
            $json = json_encode($listPhotos);
            echo $json;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit(); //Should be a message a typical user could understand
        }
    }

    /* --- GETSEARCHPHOTOS --- */
    public function getSearchPhotos($searchData)
    {
        try {
            include INCLUDES_PATH . 'db_connect.php';

            $kwords = $searchData[0];
            $startYear = $searchData[1];
            $endYear = $searchData[2];
            $wExact = $searchData[3];
            $wPart = $searchData[4];
            $searchKw = $searchData[5];
            $searchTitles = $searchData[6];
            $searchComments = $searchData[7];
            $photoPid = $searchData[8];
            $idUnique = $searchData[9];
            $idContext = $searchData[10];

            if ($startYear == "debut") {
                $startYear = "1900";
            }
            if ($endYear == "fin") {
                $endYear = "2050";
            }

            $types = "";
            $params = [];

            $sql = "SELECT id_pho, title_pho, keywords_pho,
                           caption_pho, full_pfo, preview_pfo,
                           filename_pho, pdf_pho, idgen_pho,
                           title_fol, year_pho
            FROM photos_pho pho
            INNER JOIN photos_folders_pfo pfo
            ON pfo.idfol_pfo = pho.idfol_pho
            JOIN folders_fol rpt 
            ON pfo.idfol_pfo = rpt.id_fol
            WHERE  ";

            if ($photoPid != "") {
                if ($idContext == "true") {
                    $sql .= "rpt.idtyp_fol = ? AND pho.idfol_pho = 
                             (SELECT idfol_pho FROM photos_pho pp 
                              WHERE pp.id_pho= ?)";
                    $types = "ii";
                    array_push($params, "2");
                    array_push($params, $photoPid);
                } else {
                    $sql .= "rpt.idtyp_fol = ? AND pho.id_pho = ?";
                    $types = "ii";
                    array_push($params, "2");
                    array_push($params, $photoPid);
                }
            } else {
                if (count($kwords) > 0) {
                    if ($wPart === "true") {
                        if ($searchKw === "true") {
                            $sql .= "(pho.keywords_pho LIKE ?";
                            $types = $types . 's';
                            array_push($params, "%" . $kwords[0] . "%");
                        } else {
                            if ($searchComments == "true") {
                                $sql .= "(pho.caption_pho LIKE ?";
                                $types = $types . 's';
                                array_push($params, "%" . $kwords[0] . "%");
                            } else {
                                if ($searchTitles == "true") {
                                    $sql .= "(pho.title_pho LIKE ?";
                                    $types = $types . 's';
                                    array_push($params, "%" . $kwords[0] . "%");
                                }
                            }
                        }
                        if ($searchKw == "true") {
                            for ($i = 1; $i < count($kwords); $i++) {
                                if (!empty($kwords[$i])) {
                                    $sql .= " OR pho.keywords_pho LIKE ?";
                                    $types = $types . 's';
                                    array_push($params, "%" . $kwords[$i] . "%");
                                }
                            }
                        }
                        if ($searchComments == "true") {
                            for ($i = 0; $i < count($kwords); $i++) {
                                if (!empty($kwords[$i])) {
                                    $sql .= " OR pho.caption_pho LIKE ?";
                                    $types = $types . 's';
                                    array_push($params, "%" . $kwords[$i] . "%");
                                }
                            }
                        }
                        if ($searchTitles == "true") {
                            for ($i = 0; $i < count($kwords); $i++) {
                                if (!empty($kwords[$i])) {
                                    $sql .= " OR pho.title_pho LIKE ?";
                                    $types = $types . 's';
                                    array_push($params, "%" . $kwords[$i] . "%");
                                }
                            }
                        }
                        $sql .= ")";
                    } else {
                        if ($searchKw == "true") {
                            $sql .= "(pho.keywords_pho REGEXP ?";
                            $types = $types . 's';
                            array_push($params, "[[:<:]]" . $kwords[0] .
                                "[[:>:]]");
                        } else {
                            if ($searchComments == "true") {
                                $sql .= "(pho.caption_pho REGEXP ?";
                                $types = $types . 's';
                                array_push($params, "[[:<:]]" . $kwords[0] .
                                    "[[:>:]]");
                            } else {
                                if ($searchTitles == "true") {
                                    $sql .= "(pho.title_pho REGEXP ";
                                    $types = $types . 's';
                                    array_push($params, "[[:<:]]" . $kwords[0] .
                                        "[[:>:]]");
                                }
                            }
                        }
                        if ($searchKw == "true") {
                            for ($i = 1; $i < count($kwords); $i++) {
                                if (!empty($kwords[$i])) {
                                    $sql .= " OR pho.keywords_pho REGEXP ?";
                                    $types = $types . 's';
                                    array_push($params, "[[:<:]]" .
                                        $kwords[$i] . "[[:>:]]");
                                }
                            }
                        }
                        if ($searchComments == "true") {
                            for ($i = 0; $i < count($kwords); $i++) {
                                if (!empty($kwords[$i])) {
                                    $sql .= " OR pho.caption_pho REGEXP ?";
                                    $types = $types . 's';
                                    array_push($params, "[[:<:]]" .
                                        $kwords[$i] . "[[:>:]]");
                                }
                            }
                        }
                        if ($searchTitles == "true") {
                            for ($i = 0; $i < count($kwords); $i++) {
                                if (!empty($kwords[$i])) {
                                    $sql .= " OR pho.title_pho REGEXP ?";
                                    $types = $types . 's';
                                    array_push($params, "[[:<:]]" .
                                        $kwords[$i] . "[[:>:]]");
                                }
                            }
                        }
                        $sql .= ")";
                    }
                }
                if (count($kwords) > 0) {
                    $sql .= " AND ";
                }
                $sql .= "(year_pho >= ? AND year_pho <= ?)";
                $types = $types . 'ii';
                array_push($params, $startYear);
                array_push($params, $endYear);
            }
            $sql .= " AND rpt.idtyp_fol = ? ORDER BY pho.year_pho";
            $types = $types . 'i';
            array_push($params, "2");

            $stmt = $con->prepare($sql);

            switch (count($params)) {
                case 1:
                    $stmt->bind_param($types, $params[0]);
                    break;
                case 2:
                    $stmt->bind_param($types, $params[0], $params[1]);
                    break;
                case 3:
                    $stmt->bind_param($types, $params[0], $params[1], $params[2]);
                    break;
                case 4:
                    $stmt->bind_param($types, $params[0], $params[1], $params[2],
                        $params[3]);
                    break;
                case 5:
                    $stmt->bind_param($types, $params[0], $params[1], $params[2],
                        $params[3], $params[4]);
                    break;
                case 6:
                    $stmt->bind_param($types, $params[0], $params[1], $params[2],
                        $params[3], $params[4], $params[5]);
                    break;
                case 7:
                    $stmt->bind_param($types, $params[0], $params[1], $params[1],
                        $params[3], $params[4], $params[5], $params[6]);
                    break;
                case 8:
                    $stmt->bind_param($types, $params[0], $params[1], $params[1],
                        $params[3], $params[4], $params[5], $params[6], $params[7]);
                    break;
                case 9:
                    $stmt->bind_param($types, $params[0], $params[1], $params[1],
                        $params[3], $params[4], $params[5], $params[6],
                        $params[7], $params[8]);
                    break;
                case 10:
                    $stmt->bind_param($types, $params[0], $params[1], $params[1],
                        $params[3], $params[4], $params[5], $params[6],
                        $params[7], $params[8], $params[9]);
                    break;
            };

            $stmt->execute();
            $stmt->bind_result($id, $titlePho, $keywords, $caption,
                $full, $preview, $filename, $pdf, $idgen, $titleFol,
                $year);

            $listPhotos = [];
            while ($stmt->fetch()) {
                $photo = $this->setToClass($id, $titlePho, $keywords, $caption,
                    $full, $preview, $filename, $pdf, $idgen, $titleFol,
                    $year);
                array_push($listPhotos, $photo);
            }

            $stmt->close();

            header("Content-Type:application/json");
            $json = json_encode($listPhotos);
            echo $json;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit(); //Should be a message a typical user could understand
        }
    }

    private
    function createJason($sql)
    {
        include INCLUDES_PATH . 'db_connect.php';


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

            $photo->set_Idpho($row["id_pho"]);
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
            /*      $photo->set_Height($row["height_pho"]);
                  $photo->set_Width($row["width_pho"]);*/
            if ($row["caption_pho"] == null) {
                $photo->set_Caption("");
            } else {
                $photo->set_Caption($row["caption_pho"]);
            }
            if ($row["full_pfo"] == null) {
                $photo->set_F_Path("");
            } else {
                $photo->set_F_Path($row["full_pfo"]);
            }
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
            if ($row["idgen_pho"] == null) {
                $photo->set_GeneolIdx("");
                $photo->set_GeneolNames("");
            } else {
                $gIdx = $this->buildIdxList($row["idgen_pho"]);
                $photo->set_GeneolIdx($gIdx);
                $gName = $this->buildNamesList($row['idgen_pho']);
                $photo->set_GeneolNames($gName);
            }
            if ($row['title_fol'] == null) {
                $photo->set_rptTitle("");
            } else {
                $photo->set_rptTitle($row['title_fol']);
            }
            if ($row['year_pho'] == null) {
                $photo->set_Year("");
            } else {
                $photo->set_Year($row['year_pho']);
            }

            array_push($photoArray, $photo);

            $l++;
        endwhile;

        // Free result set
        mysqli_free_result($result);

        mysqli_close($con);

        header("Content-Type:application/json");
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

    private
    function buildNamesList($idxs)
    {
        $namesList = "";
        $array = explode(',', $idxs);
        foreach ($array as $value) {
            $sql = "SELECT name_gen
            FROM geneology_idx_gen gen
            WHERE gen.id_gen = $value";

            $name = $this->getName($sql);
            if ($namesList == "") {
                $namesList = $name;
            } else {
                $namesList = $namesList . ',' . $name;
            }
        }
        return $namesList;
    }

    private
    function buildIdxList($idxs)
    {
        $idxList = "";
        $array = explode(',', $idxs);
        foreach ($array as $value) {
            $sql = "SELECT index_gen
            FROM geneology_idx_gen gen
            WHERE gen.id_gen = $value";

            $ind = $this->getIndex($sql);
            if ($idxList == "") {
                $idxList = $ind;
            } else {
                $idxList = $idxList . ',' . $ind;
            }
        }
        return $idxList;
    }

    private
    function getName($sql)
    {
        include INCLUDES_PATH . 'db_connect.php';

        if ($result = mysqli_query($con, $sql)) {
            // Return the number of rows in result set
            $rowcount = mysqli_num_rows($result);
        } else {
            echo("nothing");
        };

        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if ($row['name_gen'] == NULL) {
            return "";
        } else {
            return $row['name_gen'];
        }
    }

    private
    function getIndex($sql)
    {
        include INCLUDES_PATH . 'db_connect.php';

        if ($result = mysqli_query($con, $sql)) {
            // Return the number of rows in result set
            $rowcount = mysqli_num_rows($result);
        } else {
            echo("nothing");
        };

        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        return $row['index_gen'];
    }

    function insertPhotoInfo($photoInfo)
    {
        include INCLUDES_PATH . 'db_connect.php';
        $con->query('SET NAMES utf8');
        try {
            $photoId = $photoInfo[0];
            $title = $photoInfo[1];
            $keywords = $photoInfo[2];
            $caption = $photoInfo[3];
            $year = $photoInfo[4];
            $geneologyIdxs = $photoInfo[5];

            $sql = "UPDATE photos_pho pp
                SET pp.title_pho = ?, pp.keywords_pho = ?, pp.caption_pho = ?,
                    pp.year_pho = ?, pp.idgen_pho = ?
                WHERE pp.id_pho = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("sssisi", $title, $keywords, $caption, $year,
                $geneologyIdxs, $photoId);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit(); //Should be a message a typical user could understand
        }
    }

    function downloadPhotos($listPids)
    {
        include INCLUDES_PATH . 'db_connect.php';
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $stmt = $con->prepare("SELECT id_pho, title_pho, keywords_pho,
                                          caption_pho, full_pfo, preview_pfo,
                                          filename_pho, pdf_pho, idgen_pho,
                                          title_fol, year_pho
                             FROM photos_folders_pfo pfo
                             INNER JOIN photos_pho pho
                                 ON pfo.idfol_pfo = pho.idfol_pho
                             JOIN folders_fol rpt
                                 ON pfo.idfol_pfo = rpt.id_fol
                             WHERE id_pho IN (?)
                             ORDER BY pho.year_pho");

            $listPhotos = [];

            for ($i = 0; $i < count($listPids); $i++) {
                $stmt->bind_param("i", $listPids[$i]);
                $stmt->execute();
                $stmt->bind_result($id, $titlePho, $keywords, $caption,
                    $full, $preview, $filename, $pdf, $idgen, $titleFol,
                    $year);
                $stmt->fetch();
                $photo = $this->setToClass($id, $titlePho, $keywords, $caption,
                    $full, $preview, $filename, $pdf, $idgen, $titleFol,
                    $year);
                array_push($listPhotos, $photo);
            }

            $stmt->close();

            $curr = getcwd();
            chdir('../../');
            $curr = getcwd();

            foreach ($listPhotos as $value) {
                $p = $value->get_F_Path();
                $f = $value->get_Filename();
                $sourceName = $p . $f;
                $destName = 'photos_Normandeau_Desilets/' . $f;
                copy($sourceName, $destName);
                $value->path = 'photos_Normandeau_Desilets/';
            }
            header("Content-Type:application/json");
            $json = json_encode($listPhotos);
            return $json;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit(); //Should be a message a typical user could understand
        }
    }

    function addMetadataToMysql($idRpt, $file_name)
    {
        $curr = getcwd();
        include INCLUDES_PATH . 'db_connect.php';
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $stmt = $con->prepare("INSERT INTO photos_pho (idfol_pho, filename_pho)
                               VALUES (?,?)");
            $stmt->bind_param("is", $idRpt, utf8_encode($file_name));
            $stmt->execute();
            $stmt->close();
            return;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit(); //Should be a message a typical user could understand
        }
    }

    private function setToClass($id, $titlePho, $keywords, $caption,
                                $full, $preview, $filename, $pdf, $idgen,
                                $titleFol, $year)
    {
        $photo = new Photos();

        $photo->set_Idpho($id);
        if ($titlePho == null) {
            $photo->set_Title("");
        } else {
            $photo->set_Title($titlePho);
        }
        if ($keywords == null) {
            $photo->set_Keywords("");
        } else {
            $photo->set_Keywords($keywords);
        }
        if ($caption == null) {
            $photo->set_Caption("");
        } else {
            $photo->set_Caption($caption);
        }
        if ($full == null) {
            $photo->set_F_Path("");
        } else {
            $photo->set_F_Path($full);
        }
        if ($preview == null) {
            $photo->set_P_Path("");
        } else {
            $photo->set_P_Path($preview);
        }
        $photo->set_Filename($filename);
        if ($pdf == null) {
            $photo->set_Pdf("");
        } else {
            $photo->set_Pdf($pdf);
        }
        if ($idgen == null) {
            $photo->set_GeneolIdx("");
            $photo->set_GeneolNames("");
        } else {
            $gIdx = $this->buildIdxList($idgen);
            $photo->set_GeneolIdx($gIdx);
            $gName = $this->buildNamesList($idgen);
            $photo->set_GeneolNames($gName);
        }
        if ($titleFol == null) {
            $photo->set_rptTitle("");
        } else {
            $photo->set_rptTitle($titleFol);
        }
        if ($year == null) {
            $photo->set_Year("");
        } else {
            $photo->set_Year($year);
        }
        return $photo;
    }
}
