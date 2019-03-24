<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-23
 * Time: 14:08
 */

namespace priv\php\programs;

use priv\php\connection\DbConnection;

abstract class UpdateFolder
{
    private $con;
    private $id;
    private $ident;

    public function __construct($id,$ident)
    {
        $connection= new DbConnection();
        $this->setCon($connection->Connect());
        $this->setId($id);
        $this->setIdent($ident);
    }

    private function setCon($con): void
    {
        $this->con = $con;
    }

    public function getCon() :\mysqli
    {
        return $this->con;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setIdent($ident): void
    {
        $this->ident = $ident;
    }

    public function getIdent()
    {
        return $this->ident;
    }

    abstract function updateRow();

}