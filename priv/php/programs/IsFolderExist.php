<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-25
 * Time: 12:17
 */

namespace priv\php\programs;

use priv\php\connection\DbConnection;

class IsFolderExist
{
    private $con;
    private $level0Name;
    private $level1Name;
    private $level2Name;
    private $level3Name;
    private $fullPath;

    public function __construct($level0Name, $level1Name,
                                $level2Name, $level3Name)
    {
        $connection = new DbConnection();
        $this->setCon($connection->Connect());
        $this->setLevel0Name($level0Name);
        $this->setLevel1Name($level1Name);
        $this->setLevel2Name($level2Name);
        $this->setLevel3Name($level3Name);
    }

    public function setCon($con): void
    {
        $this->con = $con;
    }

    public function getCon(): \mysqli
    {
        return $this->con;
    }

    public function setLevel0Name($level0Name): void
    {
        $this->level0Name = $level0Name;
    }

    public function getLevel0Name()
    {
        return $this->level0Name;
    }

    public function setLevel1Name($level1Name): void
    {
        $this->level1Name = $level1Name;
    }

    public function getLevel1Name()
    {
        return $this->level1Name;
    }

    public function setLevel2Name($level2Name): void
    {
        $this->level2Name = $level2Name;
    }

    public function getLevel2Name()
    {
        return $this->level2Name;
    }

    public function setLevel3Name($level3Name): void
    {
        $this->level3Name = $level3Name;
    }

    public function getLevel3Name()
    {
        return $this->level3Name;
    }

    public function getFullPath()
    {
        return $this->fullPath;
    }

    public function setFullPath($fullPath): void
    {
        $this->fullPath = $fullPath;
    }

    public function validate()
    {
        $sql = "SELECT *
                  FROM photos_folders_pfo
                 WHERE full_pfo = ?";

        $this->createPath();
        $path = $this->getFullPath();
        $stmt = $this->getCon()->prepare($sql);
        $stmt->bind_param("s", $path);
        $stmt->execute();
        return ($stmt->fetch());
    }

    private function createPath()
    {
        $this->setFullPath("public/img/family/" . $this->getLevel0Name() .
            "/" . $this->getLevel1Name());

        if (!empty($this->getLevel2Name())) {
            $this->setFullPath($this->getFullPath() . "/" .
                $this->getLevel2Name());
        }

        if (!empty($this->getLevel3Name())) {
            $this->setFullPath($this->getFullPath() . "/" .
                $this->getLevel3Name());
        }

        $this->setFullPath($this->getFullPath() . "/full/");
    }
}