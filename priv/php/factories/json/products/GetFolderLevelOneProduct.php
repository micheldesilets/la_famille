<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-13
 * Time: 09:53
 */

namespace priv\php\factories\json\products;

use priv\php\{factories\json\factory\JsonProduct,
    connection\DbConnection, programs\CreateJson,classes\business\FolderLevels};

class GetFolderLevelOneProduct implements JsonProduct
{
    private $json;
    private $param;
    private $connection;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function getProperties()
    {
        $this->connection = new DbConnection();
        $con = $this->connection->Connect();

        mysqli_set_charset($con, "utf8");

        try {
            $sql = "SELECT id_fo1,idmem_fo1,name_fo1
                      FROM folders_one_fo1
                     WHERE idmem_fo1 = ?";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $this->param);
            $stmt->bind_result($idfo1, $idmem, $name);
            $stmt->execute();

            $arrayFol = [];
            while ($stmt->fetch()) {
                $fol = new FolderLevels($idfo1, $idmem, $name);
                array_push($arrayFol, $fol);
            }

            return $this->createJson($arrayFol);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            exit;
        }
    }

    public function createJson($json)
    {
        $this->json = new CreateJson($json);
        return $this->json->getJson();
    }
}