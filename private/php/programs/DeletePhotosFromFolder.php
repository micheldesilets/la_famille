<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-27
 * Time: 11:34
 */

include_once PRIVATE_PHP_PATH . '/programs/DeletePhotosFromDatabase.php';

class DeletePhotosFromFolder
{
    private $pids;
    private $initClass;
    private $validList;
    private $list;

    public function __construct($listPids)
    {
        $this->pids = $listPids;
        $this->deletePhotos();
    }

    private function deletePhotos()
    {
        try {
            include INCLUDES_PATH . 'db_connect.php';
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
            $this->initClass = new InitPhotoClass();

            for ($i = 0; $i < count($this->pids); $i++) {
                $stmt->bind_param("i", $this->pids[$i]);
                $stmt->execute();
                $stmt->bind_result($id, $titlePho, $keywords, $caption,
                    $full, $preview, $filename, $pdf, $idgen, $titleFol,
                    $year);
                $stmt->fetch();
                $photo = $this->initClass->setToClass($id, $titlePho, $keywords,
                    $caption,
                    $full, $preview, $filename, $pdf, $idgen, $titleFol,
                    $year);
                array_push($listPhotos, $photo);
            }

            mysqli_commit($con);
            $stmt->close();

            $this->validList = new DeletePhotosFromDatabase($listPhotos);
            $this->list = $this->validList->getValidPhotos();

            foreach ($this->list as $value) {
                $p = $value->get_F_Path();
                $f = $value->get_Filename();
                $sourceName = PROJECT_PATH . '/' . $p . $f;
                unlink($sourceName);
                $p = $value->get_P_Path();
                $sourceName = PROJECT_PATH . '/' . $p . $f;
                unlink($sourceName);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit(); //Should be a message a typical user could understand
        }
    }
}