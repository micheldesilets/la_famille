<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-15
 * Time: 09:06
 */

namespace priv\php\programs;

use priv\php\programs\FolderLevel;

class GetFoldersLevelThree extends FolderLevel
{
    public function getIdList()
    {
        $sql = "SELECT DISTINCT id_fo3
                           FROM folders_three_fo3 fo3 
                          WHERE fo3.idfo2_fo3 = ?";

        $p = $this->getParam();
        $stmt = $this->getCon()->prepare($sql);
        $stmt->bind_param("i", $this->param);
        $stmt->bind_result($idfo3);
        $stmt->execute();

        while ($stmt->fetch()) {
            array_push($this->ids, $idfo3);
        }
        return $this->ids;
    }

    public function getFolderName($id)
    {
        $sql = "SELECT name_fo3  
                  FROM folders_three_fo3
                 WHERE id_fo3 = ?";

        $stmt = $this->getCon()->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($name);
        $stmt->fetch();
        return $name;
    }

    public function hasNextLevel($val)
    {
        return true;
    }
}