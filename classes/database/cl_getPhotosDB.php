<?php

class photosBD
{
    /* --- GETSIDEBARPHOTO --- */
    public function getSidebarPhoto()
    {
        // current directory
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
        $sql = "CALL getPhotos($path)";

        $json = $this->createJason($sql);
        echo $json;
    }

    /* --- GETINFOPHOTOS --- */
    public function getInfoPhoto($pid)
    {
        $sql = "CALL getInfoPhoto($pid)";

        $json = $this->createJason($sql);
        echo $json;
    }

    /* --- GETSEARCHPHOTOS --- */
    public function getSearchPhotos($searchData)
    {
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

        $sql = "SELECT *
            FROM photos_pho pho
            INNER JOIN photosFolders_pfo pfo
            ON pfo.idrpt_pfo = pho.idrpt_pho
            JOIN repository_titles_rpt rpt 
            ON pfo.idrpt_pfo = rpt.id_rpt
            WHERE  ";

        if ($photoPid != "") {
            if ($idContext == "true") {
                $sql .= "rpt.idtyp_rpt = 2 AND pho.idrpt_pho = (SELECT idrpt_pho FROM photos_pho pp WHERE pp.id_pho= $photoPid)";
            } else {
                $sql .= "rpt.idtyp_rpt = 2 AND pho.id_pho = $photoPid";
            }
        } else {
            if (count($kwords) > 0) {
                if ($wPart === "true") {
                    if ($searchKw === "true") {
                        $sql .= "(pho.keywords_pho LIKE '%" . $kwords[0] . "%'";
                    } else {
                        if ($searchComments == "true") {
                            $sql .= "(pho.caption_pho LIKE '%" . $kwords[0] . "%'";
                        } else {
                            if ($searchTitles == "true") {
                                $sql .= "(pho.title_pho LIKE '%" . $kwords[0] . "%'";
                            }
                        }
                    }
                    if ($searchKw == "true") {
                        for ($i = 1; $i < count($kwords); $i++) {
                            if (!empty($kwords[$i])) {
                                $sql .= " OR pho.keywords_pho LIKE '%" . $kwords[$i] . "%'";
                            }
                        }
                    }
                    if ($searchComments == "true") {
                        for ($i = 0; $i < count($kwords); $i++) {
                            if (!empty($kwords[$i])) {
                                $sql .= " OR pho.caption_pho LIKE '%" . $kwords[$i] . "%'";
                            }
                        }
                    }
                    if ($searchTitles == "true") {
                        for ($i = 0; $i < count($kwords); $i++) {
                            if (!empty($kwords[$i])) {
                                $sql .= " OR pho.title_pho LIKE '%" . $kwords[$i] . "%'";
                            }
                        }
                    }
                    $sql .= ")";
                } else {
                    if ($searchKw == "true") {
                        $sql .= "(pho.keywords_pho REGEXP '[[:<:]]" . $kwords[0] . "[[:>:]]'";
                    } else {
                        if ($searchComments == "true") {
                            $sql .= "(pho.caption_pho LREGEXP '[[:<:]]" . $kwords[0] . "[[:>:]]'";
                        } else {
                            if ($searchTitles == "true") {
                                $sql .= "(pho.title_pho REGEXP '[[:<:]]" . $kwords[0] . "[[:>:]]'";
                            }
                        }
                    }
                    if ($searchKw == "true") {
                        for ($i = 1; $i < count($kwords); $i++) {
                            if (!empty($kwords[$i])) {
                                $sql .= " OR pho.keywords_pho REGEXP '[[:<:]]" . $kwords[$i] . "[[:>:]]'";
                            }
                        }
                    }
                    if ($searchComments == "true") {
                        for ($i = 0; $i < count($kwords); $i++) {
                            if (!empty($kwords[$i])) {
                                $sql .= " OR pho.caption_pho REGEXP '[[:<:]]" . $kwords[$i] . "[[:>:]]'";
                            }
                        }
                    }
                    if ($searchTitles == "true") {
                        for ($i = 0; $i < count($kwords); $i++) {
                            if (!empty($kwords[$i])) {
                                $sql .= " OR pho.title_pho REGEXP '[[:<:]]" . $kwords[$i] . "[[:>:]]'";
                            }
                        }
                    }
                    $sql .= ")";
                }
            }
            if (count($kwords) > 0) {
                $sql .= " AND ";
            }
            $sql .= "(year_pho >= '" . $startYear . "' AND year_pho <= '" . $endYear . "')";

        }
        $sql .= " AND rpt.idtyp_rpt = 2 ORDER BY pho.year_pho";

        $json = $this->createJason($sql);
        echo $json;
    }

    private
    function createJason($sql)
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
                $photo->set_GeneolIdx($this->buildIdxList($row["idgen_pho"]));
                $photo->set_GeneolNames($this->buildNamesList($row['idgen_pho']));
            }
            if ($row['title_rpt'] == null) {
                $photo->set_rptTitle("");
            } else {
                $photo->set_rptTitle($row['title_rpt']);
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
            FROM geneologyIdx_gen gen
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
            FROM geneologyIdx_gen gen
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
        include '../connection/connect.php';
        require_once '../classes/business/cl_photos.php';

        if ($result = mysqli_query($con, $sql)) {
            // Return the number of rows in result set
            $rowcount = mysqli_num_rows($result);
        } else {
            echo("nothing");
        };

        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        return $row['name_gen'];
    }

    private
    function getIndex($sql)
    {
        include '../connection/connect.php';
        require_once '../classes/business/cl_photos.php';

        if ($result = mysqli_query($con, $sql)) {
            // Return the number of rows in result set
            $rowcount = mysqli_num_rows($result);
        } else {
            echo("nothing");
        };

        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        return $row['index_gen'];

    }
}

