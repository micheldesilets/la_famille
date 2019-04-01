<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-27
 * Time: 11:25
 */

namespace priv\php\programs;

use priv\php\connection as con;

class AddMetadataToMysql
{
    private $identifier;
    private $filename;
    private $con;

    public function __construct($identifier, $fileName)
    {
        $connection = new con\DbConnection();
        $this->setCon($connection->Connect());

        $this->setIdentifier($identifier);
        $this->setFilename($fileName);
    }

    private function setCon($con): void
    {
        $this->con = $con;
    }

    private function getCon() :\mysqli
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

    private function setFilename($filename): void
    {
        $this->filename = $filename;
    }

    private function getFilename()
    {
        return $this->filename;
    }

    public function addToMysql()
    {
        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            $u = \PrivilegedUser::getByUsername($_SESSION["username"]);
            $email = $u->getEmail();

            $sql = "INSERT INTO photos_pho (
                              idfol_pho, filename_pho,owner_pho)
                       VALUES (?,?,?)";

            $stmt = $this->getCon()->prepare($sql);
            $email="michel@desilets.net";
            $stmt->bind_param("sss", $this->getIdentifier(),
                utf8_encode($this->filename), $email);
            $stmt->execute();
            $stmt->close();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            exit(); //Should be a message a typical user could understand
        }
    }
}