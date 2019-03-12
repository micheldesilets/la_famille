<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-25
 * Time: 10:50
 */

namespace priv\php\programs;

use priv\php\connection as con;
use priv\php\classes\business as business;

class SidebarPhoto
{
    private $connection;

    public function getSidebarPhoto()
    {
        $this->connection = new con\DbConnection();
        $con = $this->connection->Connect();

        try {
            $photoSb = new business\Photos();

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
        } catch (\Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }
}