<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-23
 * Time: 13:13
 */

namespace priv\php\programs;

use priv\php\programs\HasPhoto;

class HasPhotoFolderOne extends HasPhoto
{
    public function setSql(): void
    {
        $this->sql = "SELECT DISTINCT idfo1_fid
                           FROM folder_identifiers_fid
                          WHERE idfo1_fid = ?";
    }
}