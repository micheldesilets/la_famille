<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-23
 * Time: 11:23
 */

namespace priv\php\programs;

use priv\php\{connection\DbConnection};

class InsertConcatenatedFolder
{
    private $con;
    private $type;
    private $folderZero;
    private $folderOne;
    private $folderTwo;
    private $folderThree;

    public function __construct($type, $folderZero, $folderOne, $folderTwo,
                                $folderThree)
    {
        $this->connection = new DbConnection();
        $this->setCon($this->connection->Connect());

        $this->setType($type);
        $this->setFolderZero($folderZero);
        $this->setFolderOne($folderOne);
        $this->setFolderTwo($folderTwo);
        $this->setFolderThree($folderThree);
    }

    private function getCon(): \mysqli
    {
        return $this->con;
    }

    private function setCon($con): void
    {
        $this->con = $con;
    }

    private function setType($type): void
    {
        $this->type = $type;
    }

    private function getType()
    {
        return $this->type;
    }

    private function setFolderZero($folderZero): void
    {
        $this->folderZero = $folderZero;
    }

    private function getFolderZero()
    {
        return $this->folderZero;
    }

    private function setFolderOne($folderOne): void
    {
        $this->folderOne = $folderOne;
    }

    private function getFolderOne()
    {
        return $this->folderOne;
    }

    private function setFolderTwo($folderTwo): void
    {
        $this->folderTwo = $folderTwo;
    }

    private function getFolderTwo()
    {
        return $this->folderTwo;
    }

    private function setFolderThree($folderThree): void
    {
        $this->folderThree = $folderThree;
    }

    private function getFolderThree()
    {
        return $this->folderThree;
    }

    public function insertFolder()
    {
        $sql = "INSERT INTO folders_concat_fco (idtyp_fco, idmem_fco, id_fo1_fco, 
                                               id_fo2_fco, id_fo3_fco) 
                                       VALUES (?,?,?,?,?)";

        $stmt = $this->getCon()->prepare($sql);
        $type = $this->getType();
        $f0 = $this->getFolderZero();
        $f1 = $this->getFolderOne();
        $f2 = $this->getFolderTwo();
        $f3 = $this->getFolderThree();
        $stmt->bind_param("iiiii",$type,$f0,$f1,$f2,$f3);
        $stmt->execute();
        $stmt->close();
    }
}