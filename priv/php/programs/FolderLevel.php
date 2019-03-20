<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-20
 * Time: 08:28
 */

namespace priv\php\programs;

use priv\php\connection\DbConnection;

abstract class FolderLevel
{
    public function __construct($param)
    {
        $this->setParam($param);
        $this->connection = new DbConnection();
        $this->setCon($this->connection->Connect());
    }

    protected $connection;
    protected $con;
    protected $ids = [];
    protected $param;

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

    abstract protected function getIdList();

    abstract protected function getFolderName($id);

    abstract protected function hasNextLevel($val);
}