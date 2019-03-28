<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-24
 * Time: 16:21
 */

namespace priv\php\programs;

use priv\php\connection\DbConnection;

class InsertStructuredFolder
{
    private $identifier;
    private $fullPath;
    private $previewPath;
    private $fIds;
    private $con;

    public function __construct($ident, $full, $prev)
    {
        $this->connection = new DbConnection();
        $this->setCon($this->connection->Connect());
        $this->setIdentifier($ident);
        $this->setFullPath($full);
        $this->setPreviewPath($prev);
        $this->setFIds(9);
    }

    public function setCon($con): void
    {
        $this->con = $con;
    }

    public function getCon(): \mysqli
    {
        return $this->con;
    }

    public function setIdentifier($identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function getFullPath()
    {
        return $this->fullPath;
    }

    public function setFullPath($fullPath): void
    {
        $this->fullPath = $fullPath;
    }

    public function setPreviewPath($previewPath): void
    {
        $this->previewPath = $previewPath;
    }

    public function getPreviewPath()
    {
        return $this->previewPath;
    }

    public function setFIds($fIds): void
    {
        $this->fIds = $fIds;
    }

    public function getFIds()
    {
        return $this->fIds;
    }

    public function insertRow()
    {
        $sql = "INSERT INTO photos_folders_pfo (idfol_pfo,full_pfo,preview_pfo,
                          total_ids_pfo)
                  VALUES (?,?,?,?)";

        $stmt = $this->getCon()->prepare($sql);
        $ident = $this->getIdentifier();
        $full = $this->getFullPath();
        $prev = $this->getPreviewPath();
        $ids=$this->getFIds();
        $stmt->bind_param("sssi", $ident, $full, $prev, $ids);
        $stmt->execute();
    }
}