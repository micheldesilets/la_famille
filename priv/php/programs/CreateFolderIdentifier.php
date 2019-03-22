<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-21
 * Time: 08:49
 */

namespace priv\php\programs;

use priv\php\connection\DbConnection;

class CreateFolderIdentifier
{
private $con;
private $param;

public function __construct($param)
{
    $this->setParam($param);
    $this->connection = new DbConnection();
    $con = $this->connection->Connect();
    $this->setCon($con);
}

    public function setParam($param): void
    {
        $this->param = $param;
    }

    public function getParam()
    {
        return $this->param;
    }

    public function setCon($con): void
    {
        $this->con = $con;
    }

    public function getCon()
    {
        return $this->con;
    }
}