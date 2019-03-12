<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-26
 * Time: 10:54
 */

namespace priv\php\programs;

class CopyToTempFolder
{
    private $list;

    public function __construct($list)
    {
        $this->list = $list;
        $this->copyPhotos();
    }

    private function copyPhotos()
    {
        foreach ($this->list as $value) {
            $p = $value->get_F_Path();
            $f = $value->get_Filename();
            $sourceName = PROJECT_PATH . '/' . $p . $f;
            $destName = PROJECT_PATH . '/' . 'photos_Normandeau_Desilets/' .
                $f;
            copy($sourceName, $destName);
            $value->path = 'photos_Normandeau_Desilets/';
        }
    }
}