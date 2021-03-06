<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 09:33
 */

include_once 'XJsonProduct.php';
include_once 'XInitPhotoClass.php';
include_once CLASSES_PATH . '/business/cl_photos.php';

class GetPhotosProduct implements JsonProduct
{
    private $mfgProduct;
    private $initClass;
    private $param;
    private $createJson;

    public function __construct($param)
    {
        $this->param=$param;
    }

    public function getProperties($param)
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
            $stmt->bind_param($i, $this->param);
            $stmt->execute();
            $stmt->bind_result($id, $titlePho, $keywords, $caption,
                $full, $preview, $filename, $pdf, $idgen, $titleFol,
                $year);

            $listPhotos = [];
            $this->initClass = new InitPhotoClass();

            while ($stmt->fetch()) {
                $photo = $this->initClass->setToClass($id, $titlePho,
                    $keywords, $caption, $full, $preview, $filename, $pdf,
                    $idgen, $titleFol, $year);
                array_push($listPhotos, $photo);
            }

            $stmt->close();

            $this->createJson = new CreateJson($listPhotos);
            return $this->createJson->getJson();
          } catch (Exception $e) {
            error_log($e->getMessage());
            exit();
        }


    }
}