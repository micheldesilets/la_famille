<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-26
 * Time: 08:34
 */

namespace priv\php\factories\json\products;

use priv\php\{factories\json\factory as factory,connection as con,
    programs as prog,classes\business as business};

class GetObjectsProduct implements factory\JsonProduct
{
    private $json;
    private $param;
    private $connection;
    private $con;

    public function __construct($param)
    {
        $this->setParam($param);
        $this->connection = new con\DbConnection();
        $this->setCon($this->connection->Connect());
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

    public function getProperties()
    {
        try {
            $sql = "SELECT description_obj,preview_ofo,file_obj
                      FROM objects_obj obj
                           JOIN objects_folders_ofo ofo
                             ON ofo.idfol_ofo = obj.idofo_obj
                     WHERE ofo.idfol_ofo = ?
                  ORDER BY order_obj";

            $obj=$this->getParam();
            $stmt = $this->getCon()->prepare($sql);
            $stmt->bind_param("s", $obj);
            $stmt->bind_result($description, $preview, $file);
            $stmt->execute();

            $objectArray = array();
            while ($stmt->fetch()) {
                $object = new business\Objects();

                $object->set_description($description);
                $object->set_File($preview . $file);
                array_push($objectArray, $object);
            }

            $stmt->close();
            unset($stmt);

           return $this->createJson($objectArray);

        } catch (\Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }

    public function createJson($json)
    {
        $this->json = new prog\CreateJson($json);
        return $this->json->getJson();
    }
}