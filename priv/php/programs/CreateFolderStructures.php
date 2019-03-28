<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-24
 * Time: 13:29
 */

namespace priv\php\programs;

use priv\php\{connection\DbConnection,
    programs as prog,
    factories\json\factory as factory};

class CreateFolderStructures
{
    private $tree;
    private $memberClass;
    private $memberList;
    private $memberName;
    private $folderOneClass;
    private $folderOneList;
    private $folderOneName;
    private $folderTwoClass;
    private $folderTwoList;
    private $folderTwoName;
    private $folderThreeClass;
    private $folderThreeList;
    private $folderThreeName;
    private $identifier;
    private $folderInit;
    private $previewFolderInit;
    private $fullFolderMember;
    private $previewFolderMember;
    private $fullFolderOne;
    private $previewFolderOne;
    private $fullFolderTwo;
    private $previewFolderTwo;
    private $fullFolderThree;
    private $fullPath;
    private $previewPath;
    private $folderIds;
    private $memberId;
    private $folderOneId;
    private $folderTwoId;
    private $folderThreeId;
    private $folderTotal;


    public function __construct()
    {
        $this->memberClass = new GetMembers();
        $this->setMemberList($this->memberClass->getIdList());

        $table = "photos_folders_pfo";
        $this->truncate = new TruncateTable($table);
        $this->truncate->trucateTable();
    }

    private function setMemberList(array $memberList): void
    {
        $this->memberList = $memberList;
    }

    private function getMemberList(): array
    {
        return $this->memberList;
    }

    private function setMemberName($memberName): void
    {
        $this->memberName = $memberName;
    }

    private function getMemberName()
    {
        return $this->memberName;
    }

    private function setFolderOneList($folderOneList): void
    {
        $this->folderOneList = $folderOneList;
    }

    private function getFolderOneList()
    {
        return $this->folderOneList;
    }

    private function setFolderOneName($folderOneName): void
    {
        $this->folderOneName = $folderOneName;
    }

    private function getFolderOneName()
    {
        return $this->folderOneName;
    }

    private function setFolderTwoList($folderTwoList): void
    {
        $this->folderTwoList = $folderTwoList;
    }

    private function getFolderTwoList()
    {
        return $this->folderTwoList;
    }

    private function setFolderTwoName($folderTwoName): void
    {
        $this->folderTwoName = $folderTwoName;
    }

    private function getFolderTwoName()
    {
        return $this->folderTwoName;
    }

    private function setFolderThreeList($folderThreeList): void
    {
        $this->folderThreeList = $folderThreeList;
    }

    private function getFolderThreeList()
    {
        return $this->folderThreeList;
    }

    private function setFolderThreeName($folderThreeName): void
    {
        $this->folderThreeName = $folderThreeName;
    }

    private function getFolderThreeName()
    {
        return $this->folderThreeName;
    }

    public function setIdentifier($identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function setFolderInit($folderInit): void
    {
        $this->folderInit = $folderInit;
    }

    public function getFolderInit()
    {
        return $this->folderInit;
    }

    public function setPreviewFolderInit($previewFolderInit): void
    {
        $this->previewFolderInit = $previewFolderInit;
    }

    public function getPreviewFolderInit()
    {
        return $this->previewFolderInit;
    }

    public function setFullPreviewMember($fullPreviewMember): void
    {
        $this->fullFolderMember = $fullPreviewMember;
    }

    public function setFullFolderMember($fullFolderMember): void
    {
        $this->fullFolderMember = $fullFolderMember;
    }

    public function getFullFolderMember()
    {
        return $this->fullFolderMember;
    }

    public function getPreviewFolderMember()
    {
        return $this->previewFolderMember;
    }

    public function setPreviewFolderMember($previewFolderMember): void
    {
        $this->previewFolderMember = $previewFolderMember;
    }

    public function setFullFolderOne($fullFolderOne): void
    {
        $this->fullFolderOne = $fullFolderOne;
    }

    public function getFullFolderOne()
    {
        return $this->fullFolderOne;
    }

    public function setFullFolderTwo($fullFolderTwo): void
    {
        $this->fullFolderTwo = $fullFolderTwo;
    }

    public function getFullFolderTwo()
    {
        return $this->fullFolderTwo;
    }

    public function setPreviewFolderOne($previewFolderOne): void
    {
        $this->previewFolderOne = $previewFolderOne;
    }

    public function getPreviewFolderOne()
    {
        return $this->previewFolderOne;
    }

    public function setPreviewFolderTwo($previewFolderTwo): void
    {
        $this->previewFolderTwo = $previewFolderTwo;
    }

    public function getPreviewFolderTwo()
    {
        return $this->previewFolderTwo;
    }

    public function setFullFolderThree($fullFolderThree): void
    {
        $this->fullFolderThree = $fullFolderThree;
    }

    public function getFullFolderThree()
    {
        return $this->fullFolderThree;
    }

    public function setFullPath($fullPath): void
    {
        $this->fullPath = $fullPath;
    }

    public function getFullPath()
    {
        return $this->fullPath;
    }

    public function setPreviewPath($previewPath): void
    {
        $this->previewPath = $previewPath;
    }

    public function getPreviewPath()
    {
        return $this->previewPath;
    }

    public function setFolderIds($folderIds): void
    {
        $this->folderIds = $folderIds;
    }

    public function getFolderIds()
    {
        return $this->folderIds;
    }

    public function setMemberId($memberId): void
    {
        $this->memberId = $memberId;
    }

    public function getMemberId()
    {
        return $this->memberId;
    }

    public function setFolderOneId($folderOneId): void
    {
        $this->folderOneId = $folderOneId;
    }

    public function getFolderOneId()
    {
        return $this->folderOneId;
    }

    public function setFolderTwoId($folderTwoId): void
    {
        $this->folderTwoId = $folderTwoId;
    }

    public function getFolderTwoId()
    {
        return $this->folderTwoId;
    }

    public function setFolderThreeId($folderThreeId): void
    {
        $this->folderThreeId = $folderThreeId;
    }

    public function getFolderThreeId()
    {
        return $this->folderThreeId;
    }

    public function setFolderTotal($folderTotal): void
    {
        $this->folderTotal = $folderTotal;
    }

    public function getFolderTotal()
    {
        return $this->folderTotal;
    }

    public function createStructures()
    {
        $this->setFolderInit("public/img/family");

        foreach ($this->getMemberList() as $value) {
            $this->setMemberName($this->memberClass->getName($value));
            $this->setMemberId($value);

            $this->getLevelOne($value);
        }
    }

    private function getLevelOne($mem)
    {
        $this->folderOneClass = new GetFoldersLevelOne($mem);
        $this->setFolderOneList($this->folderOneClass->getIdList());

        foreach ($this->getFolderOneList() as $val) {
            $this->setFolderOneName($this->folderOneClass->getFolderName
            ($val));
            $this->setFolderOneId($val);

            if ($this->folderOneClass->hasNextLevel($val)) {
                $this->getLevelTwo($val);
            }// else {

            $this->setFullPath($this->getFolderInit() . "/"
                . $this->getMemberName() . "/" . $this->getFolderOneName() .
                "/full/");
            $this->setPreviewPath($this->getFolderInit() . "/"
                . $this->getMemberName() . "/" . $this->getFolderOneName() .
                "/preview/");

            $this->setIdentifier($this->folderOneClass->getIdentifier($val));

            //Write to file
            $this->insertRow();
              }
    }

    private function getLevelTwo($levelOne)
    {
        $this->folderTwoClass = new GetFoldersLevelTwo($levelOne);
        $this->setFolderTwoList($this->folderTwoClass->getIdList());

        foreach ($this->getFolderTwoList() as $val) {
            $this->setFolderTwoName($this->folderTwoClass->getFolderName
            ($val));
            $this->setFolderTwoId($val);

            if ($this->folderTwoClass->hasNextLevel($val)) {
                $this->getLevelThree($val);
            }
            $this->setFullPath($this->getFolderInit() . "/"
                . $this->getMemberName() . "/" . $this->getFolderOneName() .
                "/" . $this->getFolderTwoName() . "/full/");
            $this->setPreviewPath($this->getFolderInit() . "/"
                . $this->getMemberName() . "/" . $this->getFolderOneName() .
                "/" . $this->getFolderTwoName() . "/preview/");

            $this->setIdentifier($this->folderTwoClass->getIdentifier($val));

            $this->setFolderTotal($this->getMemberId() +
                $this->getFolderOneId() + $this->getFolderTwoId());
            //Write to file
            $this->insertRow();
        }
    }

    private function getLevelThree($levelTwo)
    {
        $this->folderThreeClass = new GetFoldersLevelThree($levelTwo);
        $this->setFolderThreeList($this->folderThreeClass->getIdList());

        foreach ($this->getFolderThreeList() as $val) {
            $this->SetFolderThreeName($this->folderThreeClass->getFolderName
            ($val));
            $this->setFolderThreeId($val);

            $this->setIdentifier($this->folderThreeClass->getIdentifier($val));

            $this->setFullPath($this->getFolderInit() . "/"
                . $this->getMemberName() . "/" . $this->getFolderOneName() .
                "/" . $this->getFolderTwoName() . "/" .
                $this->getFolderThreeName() . "/full/");
            $this->setPreviewPath($this->getFolderInit() . "/"
                . $this->getMemberName() . "/" . $this->getFolderOneName() .
                "/" . $this->getFolderTwoName() .
                "/" . $this->getFolderThreeName() . "/preview/");

            $this->setFolderTotal($this->getMemberId() +
                $this->getFolderOneId() +
                $this->getFolderTwoId() +
                $this->getFolderThreeId());

            //Write to file
            $this->insertRow();
        }
    }

    private function insertRow()
    {
        $structure = new InsertStructuredFolder
        ($this->getIdentifier(), $this->getFullPath(),
            $this->getPreviewPath());
        $structure->insertRow();
    }
}