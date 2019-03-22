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
    private $param;
    private $identifier;
    private $folderId;

    public function __construct($param)
    {
        $this->setParam($param);
    }

    private function setParam($param): void
    {
        $this->param = $param;
    }

    private function getParam()
    {
        return $this->param;
    }

    public function setIdentifier($identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function setFolderId($folderId): void
    {
        $this->folderId = $folderId;
    }

    public function getFolderId()
    {
        return $this->folderId;
    }

    public function addFolder()
    {
        $curr = getcwd();

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $p = $this->getParam();
        $level0Id = $p[0];
        $level1Id = $p[2];
        $level1Name = $p[3];
        $level2Id = $p[4];
        $level2Name = $p[5];
        $level3Name = $p[6];

        try {
            if ($level1Id === "0") {
                $worker = new InsertFolderOne($level0Id, $level1Name);
                $this->setFolderId($worker->insertRow());
            }

            if (empty($level2Name)) {
                //Create a unique identifier
                $identClass = new CreateUniqueIdentifier();
                $this->setIdentifier($identClass->createIdentifier());

                //Add unique identifier to mysql table
                $identifierClass = new InsertUniqueIdentifier
                ($this->getIdentifier(), "One", $this->getFolderId());
                $identifierClass->insertIdentifierRow();
                return;
            }

            if ($level2Id === "0") {
                $worker = new InsertFolderTwo($this->getFolderId(),$level2Name);
                $this->setFolderId($worker->insertRow());
            }

            if (empty($level3Name)) {
                //Create a unique identifier
                $identClass = new CreateUniqueIdentifier();
                $this->setIdentifier($identClass->createIdentifier());

                //Add unique identifier to mysql table
                $identifierClass = new InsertUniqueIdentifier
                ($this->getIdentifier(), "Two", $this->getFolderId());
                $identifierClass->insertIdentifierRow();
                return;
            }

            if ($level3Name != "") {
                $worker = new InsertFolderThree($this->getFolderId(), $level3Name);
                $this->setFolderId($worker->insertRow());

                //Create a unique identifier
                $identClass = new CreateUniqueIdentifier();
                $this->setIdentifier($identClass->createIdentifier());

                //Add unique identifier to mysql table
                $identifierClass = new InsertUniqueIdentifier
                ($this->getIdentifier(), "Three", $this->getFolderId());
                $identifierClass->insertIdentifierRow();
                return;
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }
}