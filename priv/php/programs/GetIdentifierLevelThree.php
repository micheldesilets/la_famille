<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-25
 * Time: 17:53
 */

namespace priv\php\programs;

use priv\php\connection\DbConnection;

class GetIdentifierLevelThree extends GetFolderIdentifier
{
    public function getIdentifier()
    {
        $sql="SELECT identifier_fo3
                FROM folders_three_fo3
               WHERE id_fo3 = ?";

        $stmt=$this->getCon()->prepare($sql);
        $stmt->bind_param("i",$this->getParam());
        $stmt->execute();
        return $stmt->fetch();
    }
}