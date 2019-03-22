<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-22
 * Time: 08:09
 */

namespace priv\php\programs;

use priv\php\{programs\InsertFolderRow};

class InsertFolderOne extends InsertFolderRow
{
    public function insertRow()
    {
        $levelId=$this->getFatherId();
        $levelName=$this->getFolderName();

        $sql = "INSERT INTO folders_one_fo1 (idmem_fo1, name_fo1)
                    VALUES (?,?)";
        $stmt = $this->getCon()->prepare($sql);
        $stmt->bind_param("is", $levelId, $levelName); // Level Zero = Member
        $stmt->execute();
        $this->setFolderId($stmt->insert_id);
        $stmt->close();

        return $this->getFolderId();
    }
}