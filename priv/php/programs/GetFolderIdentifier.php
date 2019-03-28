<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-25
 * Time: 14:11
 */

namespace priv\php\programs;

use priv\php\connection\DbConnection;

abstract class GetFolderIdentifier
{
    protected $connection;
    protected $con;
    protected $ids = [];
    protected $param;

    public function __construct($param)
    {
        $this->setParam($param);
        $this->connection = new DbConnection();
        $this->setCon($this->connection->Connect());
    }

    public function setParam($param)
    {
        $this->param = $param;
    }

    public function getParam()
    {
        return $this->param;
    }

    public function setCon($con)
    {
        $this->con = $con;
    }

    public function getCon(): \mysqli
    {
        return $this->con;
    }

    abstract protected function getIdentifier();
}