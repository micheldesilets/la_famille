<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-25
 * Time: 11:15
 */

namespace priv\php\factories\json\products;

use priv\php\{connection as con,programs as prog,
    factories\json\factory as factory};

class GetSearchedPhotosProduct implements factory\JsonProduct
{
    private $data;
    private $connection;
    private $param;
    private $json;
    private $initClass;

    public function __construct($param)
    {
        $this->param=$param;
    }

    public function getProperties()
    {
        try {
            $this->connection = new con\DbConnection();
            $con = $this->connection->Connect();

            $kwords = $this->param[0];
            $startYear = $this->param[1];
            $endYear = $this->param[2];
            $wExact = $this->param[3];
            $wPart = $this->param[4];
            $searchKw = $this->param[5];
            $searchTitles = $this->param[6];
            $searchComments = $this->param[7];
            $photoPid = $this->param[8];
            $idUnique = $this->param[9];
            $idContext = $this->param[10];

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

            $this->initClass = new prog\InitPhotoClass();

            while ($stmt->fetch()) {
                $photo = $this->initClass->setToClass($id, $titlePho, $keywords,
                    $caption, $full, $preview, $filename, $pdf, $idgen, $titleFol,
                    $year);
                array_push($listPhotos, $photo);
            }

            $stmt->close();

            return $this->createJson($listPhotos);
             } catch (\Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }

    public function createJson($json)
    {
        $this->json = new prog\CreateJson($json);
        return $this->json->getJson();
    }
}