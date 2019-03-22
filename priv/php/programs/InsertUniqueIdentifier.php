<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-22
 * Time: 08:07
 */

namespace priv\php\programs;

use priv\php\connection\DbConnection;

class InsertUniqueIdentifier
{
    private $con;
    private $identifier;
    private $level;
    private $folderId;

    public function __construct($ident, $level, $id)
    {
        $connection = new DbConnection();
        $this->setCon($connection->Connect());
        $this->setIdentifier($ident);
        $this->setLevel($level);
        $this->setFolderId($id);
    }

    private function setCon($con): void
    {
        $this->con = $con;
    }

    private function getCon(): \mysqli
    {
        return $this->con;
    }

    private function setIdentifier($identifier): void
    {
        $this->identifier = $identifier;
    }

    private function getIdentifier()
    {
        return $this->identifier;
    }


    private function setLevel($level): void
    {
        $this->level = $level;
    }

    private function getLevel()
    {
        return $this->level;
    }

    private function setFolderId($folderId): void
    {
        $this->folderId = $folderId;
    }

    private function getFolderId()
    {
        return $this->folderId;
    }

    public function insertIdentifierRow()
    {
        $sql = "INSERT INTO folder_identifiers_fid (identifier_fid,";
        switch ($this->getLevel()) {
            case "One":
                $sql =
                $sql = $sql . "idfo1_fid)";
                break;
            case "Two":
                $sql = $sql . "idfo2_fid)";
                break;
            case "Three":
                $sql = $sql . "idfo3_fid)";
                break;
        }
        $sql = $sql . " VALUES (?,?)";

        $stmt = $this->getCon()->prepare($sql);
        $ident = $this->getIdentifier();
        $id = $this->getFolderId();
        $stmt->bind_param("si", $ident, $id);
        $stmt->execute();
    }
}