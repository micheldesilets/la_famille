<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-15
 * Time: 09:06
 */

namespace priv\php\programs;

use priv\php\programs\FolderLevel;

class GetFoldersLevelOne extends FolderLevel
{
    public function getIdList()
    {
        $sql = "SELECT DISTINCT id_fo1
                           FROM folders_one_fo1 fo1 
                          WHERE fo1.idmem_fo1 = ?";

        $p = $this->getParam();
        $stmt = $this->getCon()->prepare($sql);
        $stmt->bind_param("i", $p);
        $stmt->bind_result($idfo1);
        $stmt->execute();

        while ($stmt->fetch()) {
            array_push($this->ids, $idfo1);
        }
        return $this->ids;
    }

    public function getFolderName($id)
    {
        $sql = "SELECT name_fo1  
                  FROM folders_one_fo1 
                 WHERE id_fo1 = ?";

        $stmt = $this->getCon()->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($name);
        $stmt->fetch();
        return $name;
    }

    public function hasNextLevel($val)
    {
        $sql = "SELECT id_fo2  
                  FROM folders_two_fo2 
                 WHERE idfo1_fo2 = ?";

        $stmt = $this->getCon()->prepare($sql);
        $stmt->bind_param("i", $val);
        $stmt->execute();
        return $stmt->fetch();
    }
}