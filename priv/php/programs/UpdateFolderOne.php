<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-23
 * Time: 14:06
 */

namespace priv\php\programs;

use priv\php\programs\UpdateFolder;

class UpdateFolderOne extends UpdateFolder
{
    public function updateRow()
    {
        $sql = "UPDATE folders_one_fo1 
                  SET identifier_fo1 = ? 
                 WHERE id_fo1 = ?";

        $stmt = $this->getCon()->prepare($sql);
        $ident = $this->getIdent();
        $id = $this->getId();
        $stmt->bind_param("si", $ident, $id);
        $stmt->execute();
    }

}