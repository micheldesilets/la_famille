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
    private $con;

    public function setCon($con): void
    {
        $this->con = $con;
    }

    public function getCon() :\mysqli
    {
        return $this->con;
    }

    public function getSidebarPhoto()
    {
        $this->connection = new con\DbConnection();
        $this->setCon($this->connection->Connect());

        try {
            $photoSb = new business\Photos();

            $sql = "SELECT hph.filename_hph, hfo.full_hfo
                    FROM home_photos_hph hph
                         JOIN parameters_par pp
                           ON id_hph = pp.home_sidebar_par   
                         JOIN home_folders_hfo hfo
                           ON hfo.idfol_hfo = hph.idfol_hph
                        WHERE pp.id_par = ?";

            $stmt = $this->getCon()->prepare($sql);
            $i = 2;
            $stmt->bind_param("i", $i);
            $stmt->execute();
            $stmt->bind_result($fileName, $fullPath);
            $stmt->fetch();

            $photoSb->set_P_Path($fullPath);
            $photoSb->set_Filename($fileName);

            $stmt->close();
            return $photoSb;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }
}