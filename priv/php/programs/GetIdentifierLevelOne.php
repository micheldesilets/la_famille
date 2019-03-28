<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-25
 * Time: 17:34
 */

namespace priv\php\programs;

use priv\php\connection\DbConnection;

class GetIdentifierLevelOne extends GetFolderIdentifier
{
public function getIdentifier()
{
   $sql="SELECT identifier_fo1
           FROM folders_one_fo1
          WHERE id_fo1 = ?";

   $stmt=$this->getCon()->prepare($sql);
   $stmt->bind_param("i",$this->getParam());
   $stmt->execute();
   return $stmt->fetch();
}
}