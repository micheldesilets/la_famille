<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-16
 * Time: 09:58
 */

include_once '../../private/initialize.php';
include_once CLASSES_PATH . '/DbConnection.php';
include_once CLASSES_PATH . '/returnJson.php';
include_once CLASSES_PATH . '/business/cl_photos.php';

class ClientGetPhotos
{
    private $con;

    public function __construct()
    {
        $db = new DbConnection();
        $this->con = $db->Connect();
    }

    public function getPhotos($path)
    {
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

            $stmt = $this->con->prepare($sql);
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

            $this->returnJson($listPhotos);

        } catch (Exception $e) {
            error_log($e->getMessage());
            exit();
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

$worker = new ClientGetPhotos();
$worker->getPhotos(13);