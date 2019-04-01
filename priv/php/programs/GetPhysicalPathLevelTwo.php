<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-29
 * Time: 11:07
 */

namespace priv\php\programs;

use priv\php\programs\{GetPhysicalPath,GetMembers};

class GetPhysicalPathLevelTwo  extends GetPhysicalPath
{
    public function getPath()
    {
        $id = $this->getId();

        $sql = "SELECT mem.first_name_mem, fo1.name_fo1, fo2.name_fo2, 
                       fo2.identifier_fo2
                  FROM folders_two_fo2 fo2
                       JOIN folders_one_fo1 fo1
                         ON fo1.id_fo1 = fo2.idfo1_fo2
                       JOIN members_mem mem
                         ON mem.id_mem = fo1.idmem_fo1
                 WHERE id_fo2=?";

        try {
            $stmt = $this->getCon()->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->bind_result($firstname,$fo1Name,$fo2Name,$identifier);
            $stmt->execute();
            $stmt->fetch();

            return $firstname . "/" . $fo1Name . "/" . $fo2Name .
                   "/" .$identifier;
        } catch
        (\Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }
}