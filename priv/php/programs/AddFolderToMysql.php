<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-13
 * Time: 10:57
 */

namespace priv\php\programs;

use priv\php\{connection\DbConnection, programs\CreateFolderIdentifier,
factories\json\factory\JsonClientEcho};

class AddFolderToMysql
{
    private $identifier;
    private $folderZeroId;
    private $folderOneId;
    private $folderOneName;
    private $folderOneIdentifier;
    private $folderTwoId;
    private $folderTwoName;
    private $folderTwoIdentifier;
    private $folderThreeId;
    private $folderThreeName;
    private $folderThreeIdentifier;

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

    public function setFolderOneIdentifier($folderOneIdentifier): void
    {
        $this->folderOneIdentifier = $folderOneIdentifier;
    }

    public function getFolderOneIdentifier()
    {
        return $this->folderOneIdentifier;
    }

    public function setFolderTwoIdentifier($folderTwoIdentifier): void
    {
        $this->folderTwoIdentifier = $folderTwoIdentifier;
    }

    public function getFolderTwoIdentifier()
    {
        return $this->folderTwoIdentifier;
    }

    public function setFolderThreeIdentifier($folderThreeIdentifier): void
    {
        $this->folderThreeIdentifier = $folderThreeIdentifier;
    }

    public function getFolderThreeIdentifier()
    {
        return $this->folderThreeIdentifier;
    }

    public function addFolder()
    {
        try {
            //Folder Level One
            if ($this->getFolderOneId() === "0") {
                //Create a unique identifier
                $this->createIdentifier();

                //Add row to folder One
                $workerF1 = new InsertFolderOne($this->getFolderZeroId(),
                    $this->getFolderOneName(), $this->getIdentifier());
                $this->setFolderOneId($workerF1->insertRow());

                //Add unique identifier to mysql table
                $identifierClass = new InsertUniqueIdentifier
                ($this->getIdentifier(), "One", $this->getFolderOneId());
                $identifierClass->insertIdentifierRow();
            }

            //   If next level is empty then...
            if (empty($this->getFolderTwoName())) {
                $worker = new JsonClientEcho(7, "Michel");
                return;
            }

            //Folder Level Two
            if ($this->getFolderTwoId() === "0") {
                //Create a unique identifier
                $this->createIdentifier();

                $worker = new InsertFolderTwo($this->getFolderOneId(),
                    $this->getFolderTwoName(), $this->getIdentifier());
                $this->setFolderTwoId($worker->insertRow());

                //Add unique identifier to mysql table
                $identifierClass = new InsertUniqueIdentifier
                ($this->getIdentifier(), "Two", $this->getFolderTwoId());
                $identifierClass->insertIdentifierRow();
            }

            //If next level is empty then...
            if (empty($this->getFolderThreeName())) {
                return;
            }

            //Create a unique identifier
            $this->createIdentifier();

            //Folder Level Three
            $worker = new InsertFolderThree($this->getFolderTwoId(),
                $this->getFolderThreeName(), $this->getIdentifier());
            $this->setFolderThreeId($worker->insertRow());

            //Add unique identifier to mysql table
            $identifierClass = new InsertUniqueIdentifier
            ($this->getIdentifier(), "Three", $this->getFolderThreeId());
            $identifierClass->insertIdentifierRow();


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