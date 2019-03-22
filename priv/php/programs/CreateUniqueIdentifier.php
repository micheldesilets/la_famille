<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-22
 * Time: 08:06
 */

namespace priv\php\programs;

use priv\php\connection\DbConnection;

class CreateUniqueIdentifier
{
    private $con;
    private $identifier;
    private $result = true;

    public function __construct()
    {
        $connection = new DbConnection();
        $this->setCon($connection->Connect());
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

    public function getIdentifier()
    {
        return $this->identifier;
    }

    private function setResult($result): void
    {
        $this->result = $result;
    }

    private function getResult()
    {
        return $this->result;
    }

    public function createIdentifier()
    {
        while ($this->getResult() === true) {
            $this->setIdentifier(substr(sha1(mt_rand()), 17, 8));
            $this->validateIdentifier($this->getIdentifier());
        }
        return $this->getIdentifier();
    }

    private function validateIdentifier($ident)
    {
        $sql = "SELECT * 
                FROM folder_identifiers_fid
               WHERE identifier_fid = ?";

        $stmt = $this->getCon()->prepare($sql);
        $stmt->bind_param("s", $ident);
        $stmt->execute();
        if ($stmt->affected_rows === -1)  {
            $r = false;
        } else {
            $r = true;
        };
        $this->setResult($r);
    }
}