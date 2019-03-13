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

class GetFolderLevelTwoProduct implements JsonProduct
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
            $sql = "SELECT id_fo2,idfo1_fo2,name_fo2
                      FROM folders_level2_fo2
                     WHERE idfo1_fo2 = ?";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $this->param);
            $stmt->bind_result($idfo2, $idfo1, $name);
            $stmt->execute();

            $arrayFo2 = [];
            while ($stmt->fetch()) {
                $fo2 = new FolderLevels($idfo2, $idfo1, $name);
                array_push($arrayFo2, $fo2);
            }

            return $this->createJson($arrayFo2);
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