<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-15
 * Time: 09:06
 */

namespace priv\php\programs;

use priv\php\connection\DbConnection;

class GetFoldersLevelOne
{
    private $connection;
    private $con;
    private $idsOne = [];
    private $param;

    public function __construct($param)
    {
        $this->param = $param;
        $this->connection = new DbConnection();
        $this->con = $this->connection->Connect();
    }

    public function getIdList()
    {
        $sql = "SELECT DISTINCT id_fo1
                           FROM folders_one_fo1 fo1 
                          WHERE fo1.idmem_fo1 = ?";

        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $this->param);
        $stmt->bind_result($idfo1);
        $stmt->execute();

        while ($stmt->fetch()) {
            array_push($this->idsOne, $idfo1);
        }
        return $this->idsOne;
    }

    public function getFolderName($id)
    {
        $sql = "SELECT name_fo1  
                  FROM folders_one_fo1 
                 WHERE id_fo1 = ?";

        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($name);
        $stmt->fetch();
        return $name;
    }
}