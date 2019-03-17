<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-14
 * Time: 16:03
 */

namespace priv\php\programs;

use priv\php\connection\DbConnection;

class GetMembers
{
    private $connection;
    private $con;
    private $members = [];

    public function __construct()
    {
        $this->connection = new DbConnection();
        $this->con = $this->connection->Connect();
    }

    public function getIdList()
    {
        $sql = "SELECT DISTINCT id_mem 
                  FROM members_mem";

        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($idmem);

        while ($stmt->fetch()) {
            array_push($this->members, $idmem);
        }
        return $this->members;
    }

    public function getNamesList()
    {

    }

    public function getName($id)
    {
        $sql = "SELECT first_name_mem 
                  FROM members_mem
                 WHERE id_mem = ?";

        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($name);
        $stmt->fetch();
        return $name;
    }
}