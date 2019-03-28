<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-21
 * Time: 17:17
 */

namespace priv\php\programs;

use priv\php\connection\DbConnection;

abstract class InsertFolderRow
{
    protected $con;
    protected $connection;
    protected $fatherId;
    protected $folderId;
    protected $folderName;
    protected $identifier;

    public function __construct($fatherId,$folderName,$identifier)
    {
        $this->connection = new DbConnection();
        $this->setCon($this->connection->Connect());
        $this->setFolderName($folderName);
        $this->setFatherId($fatherId);
        $this->setIdentifier($identifier);
    }

    public function setCon(\mysqli $con): void
    {
        $this->con = $con;
    }

    public function getCon(): \mysqli
    {
        return $this->con;
    }

    public function setFatherId($fatherId): void
    {
        $this->fatherId = $fatherId;
    }

    public function getFatherId()
    {
        return $this->fatherId;
    }

    public function setFolderId($folderId): void
    {
        $this->folderId = $folderId;
    }

    public function getFolderId()
    {
        return $this->folderId;
    }

    public function setFolderName($folderName): void
    {
        $this->folderName = $folderName;
    }

    public function getFolderName()
    {
        return $this->folderName;
    }

    public function setIdentifier($identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    abstract function insertRow();
}