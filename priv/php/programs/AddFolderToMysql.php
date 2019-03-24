<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-13
 * Time: 10:57
 */

namespace priv\php\programs;

use priv\php\{connection\DbConnection, programs\CreateFolderIdentifier};

class AddFolderToMysql
{
    private $identifier;
    private $folderZeroId;
    private $folderOneId;
    private $folderOneName;
    private $folderTwoId;
    private $folderTwoName;
    private $folderThreeId;
    private $folderThreeName;

    public function __construct($param)
    {
        $this->setFolderZeroId($param[0]);
        $this->setFolderOneId($param[2]);
        $this->setFolderTwoId($param[4]);
        $this->setfolderOneName($param[3]);
        $this->setFolderTwoName($param[5]);
        $this->setFolderThreeName($param[6]);
    }

    private function setIdentifier($identifier): void
    {
        $this->identifier = $identifier;
    }

    private function getIdentifier()
    {
        return $this->identifier;
    }

    private function setFolderZeroId($folderZeroId): void
    {
        $this->folderZeroId = $folderZeroId;
    }

    private function getFolderZeroId()
    {
        return $this->folderZeroId;
    }

    private function setFolderOneId($folderOneId): void
    {
        $this->folderOneId = $folderOneId;
    }

    private function getFolderOneId()
    {
        return $this->folderOneId;
    }

    private function setFolderTwoId($folderTwoId): void
    {
        $this->folderTwoId = $folderTwoId;
    }

    private function getFolderTwoId()
    {
        return $this->folderTwoId;
    }

    private function setFolderOneName($folderOneName): void
    {
        $this->folderOneName = $folderOneName;
    }

    private function getFolderOneName()
    {
        return $this->folderOneName;
    }

    private function setFolderTwoName($folderTwoName): void
    {
        $this->folderTwoName = $folderTwoName;
    }

    private function getFolderTwoName()
    {
        return $this->folderTwoName;
    }

    private function setFolderThreeId($folderThreeId): void
    {
        $this->folderThreeId = $folderThreeId;
    }

    private function getFolderThreeId()
    {
        return $this->folderThreeId;
    }

    private function setFolderThreeName($folderThreeName): void
    {
        $this->folderThreeName = $folderThreeName;
    }

    private function getFolderThreeName()
    {
        return $this->folderThreeName;
    }

    public function addFolder()
    {
        try {
            //Folder Level One
            if ($this->getFolderOneId() === "0") {
                $workerF1 = new InsertFolderOne($this->getFolderZeroId(),
                    $this->getFolderOneName());
                $this->setFolderOneId($workerF1->insertRow());
            }
            //If next level is empty then...
            if (empty($this->getFolderTwoName())) {
                //Create a unique identifier
                $this->createIdentifier();

                //Add unique identifier to mysql table
                $identifierClass = new InsertUniqueIdentifier
                ($this->getIdentifier(), "One", $this->getFolderOneId());
                $identifierClass->insertIdentifierRow();

                //Update identifier folder one
                $updateFolderClass=new UpdateFolderOne
                ($this->getFolderOneId(),$this->getIdentifier
                ());
                $updateFolderClass->updateRow();

                //Insert new row into folder_concat_fco
/*                $type = 2;
                $concatClass = new InsertConcatenatedFolder
                ($type, $this->getFolderZeroId(), $this->getFolderOneId(),
                   NULL,NULL);
                $concatClass->insertFolder();*/
                return;
            }

            //Folder Level Two
            if ($this->getFolderTwoId() === "0") {
                $worker = new InsertFolderTwo($this->getFolderOneId(),
                    $this->getFolderTwoName());
                $this->setFolderTwoId($worker->insertRow());
            }
            //If next level is empty then...
            if (empty($this->getFolderThreeName())) {
                //Create a unique identifier
                $this->createIdentifier();

                //Add unique identifier to mysql table
                $identifierClass = new InsertUniqueIdentifier
                ($this->getIdentifier(), "Two", $this->getFolderTwoId());
                $identifierClass->insertIdentifierRow();

                //Update identifier folder two
                $updateFolderClass=new UpdateFolderTwo
                ($this->getFolderTwoId(),$this->getIdentifier
                ());
                $updateFolderClass->updateRow();
                //Insert new row into folder_concat_fco
/*                $type = 2;
                $concatClass = new InsertConcatenatedFolder
                ($type, $this->getFolderZeroId(), $this->getFolderOneId(),
                    $this->getFolderTwoId(),NULL);
                $concatClass->insertFolder();*/
                return;
            }

            //Folder Level Three
            $worker = new InsertFolderThree($this->getFolderTwoId(),
                $this->getFolderThreeName());
            $this->setFolderThreeId($worker->insertRow());

            //Create a unique identifier
            $this->createIdentifier();

            //Add unique identifier to mysql table
            $identifierClass = new InsertUniqueIdentifier
            ($this->getIdentifier(), "Three", $this->getFolderThreeId());
            $identifierClass->insertIdentifierRow();

            //Update identifier folder three
            $updateFolderClass=new UpdateFolderThree
            ($this->getFolderThreeId(),$this->getIdentifier
            ());
            $updateFolderClass->updateRow();
            //Insert new row into folder_concat_fco
/*            $type = 2;
            $concatClass = new InsertConcatenatedFolder
            ($type, $this->getFolderZeroId(), $this->getFolderOneId(),
                $this->getFolderTwoId(), $this->getFolderThreeId());
            $concatClass->insertFolder();*/
            return;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }

    private function createIdentifier()
    {
        $identClass = new CreateUniqueIdentifier();
        $this->setIdentifier($identClass->createIdentifier());
    }
}