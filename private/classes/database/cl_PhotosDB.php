<?php

require_once CLASSES_PATH . '/business/cl_photos.php';
include_once INCLUDES_PATH . 'functions.php';
require_once INCLUDES_PATH . "Role.php";
require_once INCLUDES_PATH . "PrivilegedUser.php";

sec_session_start();

class photosDB
{
    /* --- GETSIDEBARPHOTO --- */
    public function getSidebarPhoto()
    {
        include INCLUDES_PATH . 'db_connect.php';
        try {
            $photoSb = new Photos();

            $sql="SELECT pho.filename_pho, pfo.full_pfo
                    FROM photos_pho pho
                         JOIN parameters_par pp
                           ON id_pho = pp.home_sidebar_par   
                         JOIN photos_folders_pfo pfo
                           ON pfo.idfol_pfo = pho.idfol_pho
                        WHERE pp.id_par = ?";

            $stmt = $con->prepare($sql);
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
            exit();
        }
    }

    /* --- GETPHOTOS --- */
    public function getPhotos($path)
    {
        include INCLUDES_PATH . 'db_connect.php';
        try {
            $sql = "SELECT id_pho, title_pho, keywords_pho, caption_pho, 
                           full_pfo, preview_pfo, filename_pho, pdf_pho, 
                           idgen_pho, title_fol, year_pho
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
            exit();
        }
    }

    /* --- GETINFOPHOTOS --- */
    public function getInfoPhoto($pid)
    {
        try {
            include INCLUDES_PATH . 'db_connect.php';

            $sql = "SELECT id_pho, title_pho, keywords_pho, caption_pho, 
                           full_pfo, preview_pfo, filename_pho, pdf_pho, 
                           idgen_pho, title_fol, year_pho
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
            exit();
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
                     WHERE";

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
            exit();
        }
    }

    private
    function buildNamesList($idxs)
    {
        include INCLUDES_PATH . 'db_connect.php';

        $namesList = "";
        $array = explode(',', $idxs);

        $sql = "SELECT name_gen
                  FROM geneology_idx_gen gen
                 WHERE gen.id_gen = ?";
        $stmt = $con->prepare($sql);
        try {
            foreach ($array as $value) {
                $stmt->bind_param("s", $value);
                $stmt->execute();
                $data = $stmt->get_result()->fetch_all();

                $n = $data[0];

                if ($namesList === "") {
                    $namesList = $n[0];
                } else {
                    $namesList = $namesList . ',' . $n[0];
                }
            }
            return $namesList;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }

    private function buildIdxList($idxs)
    {
        include INCLUDES_PATH . 'db_connect.php';

        $idxList = "";
        $list = "";
        $array = explode(',', $idxs);
        try {
            $sql = "SELECT index_gen
                      FROM geneology_idx_gen gen
                     WHERE gen.id_gen = ?";

            $stmt = $con->prepare($sql);

            foreach ($array as $value) {
                $stmt->bind_param("s", $value);
                $stmt->execute();
                $data = $stmt->get_result()->fetch_all();

                $n = $data[0];

                if ($idxList == "") {
                    $idxList = strval($n[0]);
                } else {
                    $idxList = $idxList . ',' . strval($n[0]);
                }
            }
            return $idxList;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit(); //Should be a message a typical user could understand
        }
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
                       SET pp.title_pho = ?, pp.keywords_pho = ?, 
                           pp.caption_pho = ?, pp.year_pho = ?, pp.idgen_pho = ?
                     WHERE pp.id_pho = ?";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("sssisi", $title, $keywords, $caption, $year,
                              $geneologyIdxs, $photoId);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }

    function downloadPhotos($listPids)
    {
        try {
            include INCLUDES_PATH . 'db_connect.php';
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            $sql="SELECT id_pho, title_pho, keywords_pho, caption_pho, full_pfo, 
                         preview_pfo, filename_pho, pdf_pho, idgen_pho,
                         title_fol, year_pho
                    FROM photos_folders_pfo pfo
                         INNER JOIN photos_pho pho
                                 ON pfo.idfol_pfo = pho.idfol_pho
                               JOIN folders_fol rpt
                                 ON pfo.idfol_pfo = rpt.id_fol
                   WHERE id_pho IN (?)
                ORDER BY pho.year_pho";

            $stmt = $con->prepare($sql);

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
        try {
            include INCLUDES_PATH . 'db_connect.php';
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            $u = PrivilegedUser::getByUsername($_SESSION["username"]);
            $email = $u->getEmail();

            $sql="INSERT INTO photos_pho (
                              idfol_pho, filename_pho,owner_pho)
                       VALUES (?,?,?)";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("iss", $idRpt, utf8_encode($file_name),$email);
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
