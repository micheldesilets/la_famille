<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-28
 * Time: 13:42
 */

namespace priv\php\programs;

use priv\php\connection\DbConnection;

abstract class GetPhysicalPath
{
    protected $id;
    protected $con;

    public function __construct($id)
    {
        $this->connection = new DbConnection();
        $this->setCon($this->connection->Connect());
        $this->setId($id);
    }

    private function setCon($con): void
    {
        $this->con = $con;
    }

    public function getCon(): \mysqli
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

    abstract function getPath();
}