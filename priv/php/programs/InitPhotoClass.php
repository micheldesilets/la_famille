<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-24
 * Time: 14:42
 */

namespace priv\php\programs;

use priv\php\programs as prog;
use priv\php\classes\business as business;

class InitPhotoClass
{
    private $namesList;
    private $indexesList;

    public function setToClass($id, $titlePho, $keywords, $caption,
                               $full, $preview, $filename, $pdf, $idgen,
                               $titleFol, $year)
    {
        $this->namesList = new prog\GeneologyNamesList();
        $this->indexesList=new prog\GeneologyIndexesList();
        $photo = new business\Photos();

        $photo->set_Idpho($id);
        if ($titlePho == null) {
            $photo->set_Title("");
        } else {
            $photo->set_Title($titlePho);
        }
        if ($keywords == null) {
            $photo->set_Keywords("");
        } else {
            $photo->set_Keywords($keywords);
        }
        if ($caption == null) {
            $photo->set_Caption("");
        } else {
            $photo->set_Caption($caption);
        }
        if ($full == null) {
            $photo->set_F_Path("");
        } else {
            $photo->set_F_Path($full);
        }
        if ($preview == null) {
            $photo->set_P_Path("");
        } else {
            $photo->set_P_Path($preview);
        }
        $photo->set_Filename($filename);
        if ($pdf == null) {
            $photo->set_Pdf("");
        } else {
            $photo->set_Pdf($pdf);
        }
        if ($idgen == null) {
            $photo->set_GeneolIdx("");
            $photo->set_GeneolNames("");
        } else {
            $gIdx = $this->indexesList->buildIdxList($idgen);
            $photo->set_GeneolIdx($gIdx);
            $gName = $this->namesList->buildNamesList($idgen);
            $photo->set_GeneolNames($gName);
        }
        if ($titleFol == null) {
            $photo->set_rptTitle("");
        } else {
            $photo->set_rptTitle($titleFol);
        }
        if ($year == null) {
            $photo->set_Year("");
        } else {
            $photo->set_Year($year);
        }
        return $photo;
    }
}