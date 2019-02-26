<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 09:33
 */

include_once 'FormatHelper.php';
include_once 'JsonProduct.php';
include_once '../private/initialize.php';
include_once CLASSES_PATH . '/business/cl_photos.php';

class GetSidebarPhotoProduct implements JsonProduct
{
    private $mfgProduct;

    public function getProperties()
    {
        include INCLUDES_PATH . 'db_connect.php';

        try {
            $photoSb = new Photos();

            $sql = "SELECT pho.filename_pho, pfo.full_pfo
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
            $stmt->bind_result($fileName, $fullPath);
            $stmt->fetch();
            $stmt->close();

            $photoSb->set_P_Path($fullPath);
            $photoSb->set_Filename($fileName);

            return $photoSb;
            //return $this->mfgProduct;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit();
        }


    }
}