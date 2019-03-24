<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-23
 * Time: 14:07
 */

namespace priv\php\programs;

use priv\php\programs\UpdateFolder;

class UpdateFolderThree extends UpdateFolder
{
    public function updateRow()
    {
        $sql = "UPDATE folders_two_fo3 
                  SET= indetifier_fo3 = ? 
                 WHERE id_fo3 = ?";

        $stmt = $this->getCon()->prepare($sql);
        $ident = $this->getIdent();
        $id = $this->getId();
        $stmt->bind_param("si", $ident, $id);
        $stmt->execute();
    }
}