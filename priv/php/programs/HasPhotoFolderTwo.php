<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-23
 * Time: 13:14
 */

namespace priv\php\programs;

use priv\php\programs\HasPhoto;

class HasPhotoFolderTwo extends HasPhoto
{
    public function setSql(): void
    {
        $this->sql = "SELECT DISTINCT idfo2_pho
                           FROM photos_pho
                          WHERE idfo2_pho = ?";
    }
}