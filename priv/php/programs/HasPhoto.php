<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-18
 * Time: 08:58
 */

namespace priv\php\programs;

use priv\php\connection\DbConnection;

class HasPhoto
{
//    private $param;
    private $con;

    public function __construct()
    {
//        $this->param = $param;
        $this->connection = new DbConnection();
        $this->con = $this->connection->Connect();
    }

    public function hasPhoto($param)
    {
        try {
            $sql = "SELECT DISTINCT idfol_pho
                           FROM photos_pho
                          WHERE idfol_pho = ?";

            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("i", $param);
            $stmt->execute();
            return $stmt->fetch();
        }catch (\Exception $e) {
            error_log($e->getMessage());
            exit;
        }finally{
            $stmt->close();
        }
    }
}