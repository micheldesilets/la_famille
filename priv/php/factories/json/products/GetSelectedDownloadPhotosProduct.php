<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-25
 * Time: 13:20
 */

namespace priv\php\factories\json\products;

use priv\php\{factories\json\factory as factory,connection as con,
    programs as prog};

class GetSelectedDownloadPhotosProduct implements factory\JsonProduct
{
    private $cp;
    private $json;
    private $initClass;
    private $param;
    private $connection;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function getProperties()
    {
        try {
            $this->connection = new con\DbConnection();
            $con = $this->connection->Connect();

            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            mysqli_autocommit($con, FALSE);

            $sql = "SELECT id_pho, title_pho, keywords_pho, caption_pho, full_pfo, 
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

            for ($i = 0; $i < count($this->param); $i++) {
                $stmt->bind_param("i", $this->param[$i]);
                $stmt->execute();
                $stmt->bind_result($id, $titlePho, $keywords, $caption,
                    $full, $preview, $filename, $pdf, $idgen, $titleFol,
                    $year);
                $stmt->fetch();

                $this->initClass = new prog\InitPhotoClass();

                $photo = $this->initClass->setToClass($id, $titlePho,
                    $keywords, $caption,
                    $full, $preview, $filename, $pdf, $idgen, $titleFol,
                    $year);
                array_push($listPhotos, $photo);
            }

            mysqli_commit($con);
            $stmt->close();

            $this->cp = new prog\CopyToTempFolder($listPhotos);

            return $this->createJson($listPhotos);

        } catch (\Exception $e) {
            error_log($e->getMessage());
            exit(); //Should be a message a typical user could understand
        }
    }

    public function createJson($json)
    {
        $this->json = new prog\CreateJson($json);
        return $this->json->getJson();
    }
}