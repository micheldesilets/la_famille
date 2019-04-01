<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-29
 * Time: 10:46
 */

namespace priv\php\programs;

use priv\php\programs\GetPhysicalPath;

class GetPhysicalPathLevelOne extends GetPhysicalPath
{
    public function getPath()
    {
        $id = $this->getId();

        $sql = "SELECT mem.first_name_mem, fo1.name_fo1, fo1.identifier_fo1
                  FROM members_mem mem
                       JOIN folders_one_fo1 fo1 
                         ON mem.id_mem = fo1.idmem_fo1
                 WHERE id_fo1 = ?";

        try {
            $stmt = $this->getCon()->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->bind_result($firstname, $fo1Name, $identifier);
            $stmt->execute();
            $stmt->fetch();

            return $firstname . "/" . $fo1Name . "/" . $identifier;
        } catch
        (\Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }
}