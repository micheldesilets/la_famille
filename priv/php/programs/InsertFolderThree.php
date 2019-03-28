<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-22
 * Time: 08:10
 */

namespace priv\php\programs;

use priv\php\{programs\InsertFolderRow};

class InsertFolderThree extends InsertFolderRow
{
    public function insertRow()
    {
        $levelId=$this->getFatherId();
        $levelName=$this->getFolderName();
        $identifier=$this->getIdentifier();

        $sql = "INSERT INTO folders_three_fo3 (idfo2_fo3, name_fo3,identifier_fo3)
                    VALUES (?,?,?)";
        $stmt = $this->getCon()->prepare($sql);
        $stmt->bind_param("iss", $levelId, $levelName,$identifier);
        $stmt->execute();
        $this->setFolderId($stmt->insert_id);
        $stmt->close();

        return $this->getFolderId();
    }
}