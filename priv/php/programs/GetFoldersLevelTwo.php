<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-15
 * Time: 09:06
 */

namespace priv\php\programs;

use priv\php\connection\DbConnection;

class GetFoldersLevelTwo
{
    private $connection;
    private $con;
    private $idsTwo = [];
    private $param;

    public function __construct($param)
    {
        $this->param = $param;
        $this->connection = new DbConnection();
        $this->con = $this->connection->Connect();
    }

    public function getIdList()
    {
        $sql = "SELECT DISTINCT id_fo2
                           FROM folders_two_fo2 fo2 
                          WHERE fo2.idfo1_fo2 = ?";

        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $this->param);
        $stmt->bind_result($idfo2);
        $stmt->execute();

        while ($stmt->fetch()) {
            array_push($this->idsTwo, $idfo2);
        }
        return $this->idsTwo;
    }

    public function getFolderName($id)
    {
        $sql = "SELECT name_fo2  
                  FROM folders_two_fo2 
                 WHERE id_fo2 = ?";

        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($name);
        $stmt->fetch();
        return $name;
    }
}