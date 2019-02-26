<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-25
 * Time: 13:20
 */

class GetSelectedDownloadPhotos
{
    private $listPids;

    public function __construct($listPids)
    {
    $this->listPids=$listPids;
    }

    function getDownloadPhotos()
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

            for ($i = 0; $i < count($this->$listPids); $i++) {
                $stmt->bind_param("i", $this->$listPids[$i]);
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

            mysqli_commit($con);
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

            $this->returnJson($listPhotos);

        } catch (Exception $e) {
            error_log($e->getMessage());
            exit(); //Should be a message a typical user could understand
        }
    }
}