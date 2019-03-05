<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-25
 * Time: 10:58
 */

include_once PRIVATE_PHP_PATH . '/factories/json/factory/JsonProduct.php';

class GetPhotoMetadataProduct implements JsonProduct
{
    private $initClass;
    private $param;
    private $json;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function getProperties()
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
            $stmt->bind_param("i", $this->param);
            $stmt->execute();
            $stmt->bind_result($id, $titlePho, $keywords, $caption,
                $full, $preview, $filename, $pdf, $idgen, $titleFol,
                $year);

            $listPhotos = [];
            $stmt->fetch();

            $this->initClass = new InitPhotoClass();

            $photo = $this->initClass->setToClass($id, $titlePho, $keywords, $caption,
                $full, $preview, $filename, $pdf, $idgen, $titleFol,
                $year);
            array_push($listPhotos, $photo);

            $stmt->close();

            return $this->createJson($listPhotos);

        } catch (Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }

    public function createJson($json)
    {
        $this->json = new CreateJson($json);
        return $this->json->getJson();
    }
}