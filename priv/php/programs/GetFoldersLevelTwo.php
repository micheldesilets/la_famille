<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-15
 * Time: 09:06
 */

namespace priv\php\programs;

use priv\php\programs\FolderLevel;

class GetFoldersLevelTwo extends FolderLevel
{
    public function getIdList()
    {
        $sql = "SELECT DISTINCT id_fo2
                           FROM folders_two_fo2 fo2 
                          WHERE fo2.idfo1_fo2 = ?";

        $p = $this->getParam();
        $stmt = $this->getCon()->prepare($sql);
        $stmt->bind_param("i", $p);
        $stmt->bind_result($idfo2);
        $stmt->execute();

        while ($stmt->fetch()) {
            array_push($this->ids, $idfo2);
        }
        return $this->ids;
    }

    public function getFolderName($id)
    {
        $sql = "SELECT name_fo2  
                  FROM folders_two_fo2 
                 WHERE id_fo2 = ?";

        $stmt = $this->getCon()->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($name);
        $stmt->fetch();
        return $name;
    }

    public function hasNextLevel($val){
        $sql = "SELECT id_fo3  
                  FROM folders_three_fo3 
                 WHERE idfo2_fo3 = ?";

        $stmt = $this->getCon()->prepare($sql);
        $stmt->bind_param("i", $val);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getIdentifier($val){
        $sql = "SELECT identifier_fo2  
                  FROM folders_two_fo2 
                 WHERE id_fo2 = ?";

        $stmt = $this->getCon()->prepare($sql);
        $stmt->bind_param("i", $val);
        $stmt->execute();
        $stmt->bind_result($identifier);
        $stmt->fetch();
        return $identifier;
    }
}