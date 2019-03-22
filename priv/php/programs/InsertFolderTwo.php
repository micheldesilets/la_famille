<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-22
 * Time: 08:10
 */

namespace priv\php\programs;

use priv\php\{programs\InsertFolderRow};

class InsertFolderTwo extends InsertFolderRow
{
    public function insertRow()
    {
        $levelId=$this->getFatherId();
        $levelName=$this->getFolderName();

        $sql = "INSERT INTO folders_two_fo2 (idfo1_fo2, name_fo2)
                    VALUES (?,?)";
        $stmt = $this->getCon()->prepare($sql);
        $stmt->bind_param("is", $levelId, $levelName);
        $stmt->execute();
        $this->setFolderId($stmt->insert_id);
        $stmt->close();

        return $this->getFolderId();
    }
}