<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-25
 * Time: 17:51
 */

namespace priv\php\programs;

use priv\php\connection\DbConnection;

class GetIdentifierLevelTwo Extends GetFolderIdentifier
{
    public function getIdentifier()
    {
        $sql="SELECT identifier_fo2
           FROM folders_two_fo2
          WHERE id_fo2 = ?";

        $stmt=$this->getCon()->prepare($sql);
        $stmt->bind_param("i",$this->getParam());
        $stmt->execute();
        return $stmt->fetch();
    }
}