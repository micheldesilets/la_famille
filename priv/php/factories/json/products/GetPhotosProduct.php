<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 09:33
 */

namespace priv\php\factories\json\products;

use priv\php\{factories\json\factory as factory,
    connection as con,
    programs as prog};

class GetPhotosProduct implements factory\JsonProduct
{
    private $connection;
    private $initClass;
    private $param;
    private $json;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function getProperties()
    {
        $this->connection = new con\DbConnection();
        $con = $this->connection->Connect();

        try {
            $sql = "SELECT id_pho, title_pho, keywords_pho, caption_pho, 
                           full_pfo, preview_pfo, filename_pho, pdf_pho, 
                           idgen_pho, year_pho
                      FROM photos_folders_pfo pfo
                           INNER JOIN photos_pho pho
                                   ON pfo.idfol_pfo = pho.idfol_pho
                     WHERE pfo.idfol_pfo = ?
                  ORDER BY pho.year_pho";

            $stmt = $con->prepare($sql);
            $i = "i";
            $stmt->bind_param($i, $this->param);
            $stmt->execute();
            $stmt->bind_result($id, $titlePho, $keywords, $caption,
                $full, $preview, $filename, $pdf, $idgen,
                $year);

            $listPhotos = [];
            $this->initClass = new prog\InitPhotoClass();

            while ($stmt->fetch()) {
                $photo = $this->initClass->setToClass($id, $titlePho,
                    $keywords, $caption, $full, $preview, $filename, $pdf,
                    $idgen, $year);
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