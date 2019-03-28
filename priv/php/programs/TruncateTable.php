<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-25
 * Time: 08:23
 */

namespace priv\php\programs;

use priv\php\connection\DbConnection;

class TruncateTable
{
    private $con;
    private $param;

    public function __construct($param)
    {
        $connection = new DbConnection();
        $this->setCon($connection->Connect());
        $this->setParam($param);
    }

    public function setCon($con): void
    {
        $this->con = $con;
    }

    public function getCon() :\mysqli
    {
        return $this->con;
    }

    public function setParam($param): void
    {
        $this->param = $param;
    }

    public function getParam()
    {
        return $this->param;
    }

    public function trucateTable()
    {
        $sql="TRUNCATE TABLE " .
            $this->getParam();

        $stmt=$this->getCon()->prepare($sql);
        $stmt->execute();
        }

}