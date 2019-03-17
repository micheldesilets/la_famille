<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-14
 * Time: 13:58
 */

namespace priv\php\programs;

use priv\php\factories\json\products\GetFolderLevelOneProduct;

class BuildFolderTree
{
    private $jsonTree;
    private $data;
    private $htmlString = '';
    private $membersClass;
    private $memberList = array();
    private $memberName;
    private $folderOneClass;
    private $folderOneList = array();
    private $folderOneName;
    private $folderTwoClass;
    private $folderTwoList = array();
    private $folderTwoName;
    private $folderThreeClass;
    private $folderThreeList = array();
    private $folderThreeName;

    private $j = 0;
    private $item = 1;
    private $subItem = 1;

    public function __construct($jsonTree)
    {
        $this->jsonTree = $jsonTree;
        $this->data = json_decode($this->jsonTree);
        $this->membersClass = new GetMembers();
        $this->memberList = $this->membersClass->getIdList();
    }

    public function buildTree()
    {
        foreach ($this->memberList as $value) {
            $this->memberName = $this->membersClass->getName($value);

            $this->htmlString = $this->htmlString .
                "<input type=\"checkbox\" id=\"folders__menu" .
                (string)$this->j .
                "\"/>\n" .
                "<label for=\"folders__menu" .
                (string)$this->j .
                "\" class=\"folders__names\">" .
                $this->memberName .
                "</label>\n" .
                "<div class=\"folders__multi-level" .
                (string)$this->j .
                "\">\n";

            $this->getLevelOne($value);

            $this->htmlString = $this->htmlString .
                "</div>\n";

            $this->j += 1;
        }
        $this->htmlString = $this->htmlString .
            "</div>\n" .
            "</div>\n";

        return $this->htmlString;
    }

    private function getLevelOne($Mem)
    {
        $this->folderOneClass = new GetFoldersLevelOne($Mem);
        $this->folderOneList = $this->folderOneClass->getIdList();

        foreach ($this->folderOneList as $val) {
            $this->folderOneName = $this->folderOneClass->getFolderName($val);
            $this->htmlString = $this->htmlString .
                "<div class=\"folders__item\">\n" .
                "<input type=\"checkbox\" id=\"IT" .
                (string)$this->item .
                "\"/>\n" .
                "<img src=\"../../public/img/icons/arrow.png\" " .
                "class=\"folders__arrow\">\n" .
                "<label for=\"IT" .
                (string)$this->item .
                "\">" .
                $this->folderOneName .
                "</label>\n";

            $this->getLevelTwo($val);
            $this->item += 1;
            $this->subItem = 0;

            $this->htmlString = $this->htmlString .
                "</ul>\n" .
                "</div>\n";
        }
    }

    private function getLevelTwo($levelOne)
    {
        $this->folderTwoClass = new GetFoldersLevelTwo($levelOne);
        $this->folderTwoList = $this->folderTwoClass->getIdList();

        $this->htmlString = $this->htmlString .
            "<ul>\n";

        foreach ($this->folderTwoList as $val) {
            $this->folderTwoName = $this->folderTwoClass->getFolderName($val);

            $this->htmlString = $this->htmlString .
                "<li>\n" .
                "<div class=\"folders__sub-item\">\n" .
                "<input type=\"checkbox\" id=\"SIT" .
                (string)$this->item .
                "-" .
                (string)$this->subItem .
                "\"/>\n" .
                "<img src=\"../../public/img/icons/arrow.png\" " .
                "class=\"folders__arrow\">\n" .
                "<label for=\"SIT" .
                (string)$this->item .
                "-" .
                (string)$this->subItem .
                "\">" .
                $this->folderTwoName .
                "</label>\n";

            $this->getLevelThree($val);
            $this->subItem += 1;

            $this->htmlString = $this->htmlString .
                "</ul>\n" .
                "</div>\n" .
                "</li>\n";
        }
    }

    private function getLevelThree($levelTwo)
    {
        $this->folderThreeClass = new GetFoldersLevelThree($levelTwo);
        $this->folderThreeList = $this->folderThreeClass->getIdList();

        $this->htmlString = $this->htmlString .
            "<ul>\n";

        foreach ($this->folderThreeList as $val) {
            $this->folderThreeName = $this->folderThreeClass->getFolderName
            ($val);

            $this->htmlString = $this->htmlString .
                "<li class=\"folders__photofolder\" value=\"0\" " .
                "onclick=\"getFamilyPhotos(this," .
                "13" .
                ")\">" .
                $this->folderThreeName .
                "</li>\n";
        }
    }
}