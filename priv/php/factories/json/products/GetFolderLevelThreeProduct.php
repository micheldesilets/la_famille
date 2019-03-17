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

class GetFolderLevelThreeProduct implements JsonProduct
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
            $sql = "SELECT id_fo3,idfo2_fo3,name_fo3
                      FROM folders_three_fo3
                     WHERE idfo2_fo3 = ?";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $this->param);
            $stmt->bind_result($idfo3, $idfo2, $name);
            $stmt->execute();

            $arrayFo3 = [];
            while ($stmt->fetch()) {
                $fo3 = new FolderLevels($idfo3, $idfo2, $name);
                array_push($arrayFo3, $fo3);
            }

            return $this->createJson($arrayFo3);
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