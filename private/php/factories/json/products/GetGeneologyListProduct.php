<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-05
 * Time: 09:56
 */

include_once PRIVATE_PHP_PATH . '/factories/json/factory/JsonProduct.php';
include_once PRIVATE_PHP_PATH . '/connection/DbConnection.php';

class GetGeneologyListProduct implements JsonProduct
{
    private $param;
    private $json;
    private $connection;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function getProperties()
    {
        $this->connection = new DbConnection();
        $con = $this->connection->Connect();

        require_once CLASSES_PATH . '/business/cl_geneology.php';

        $sql = "SELECT id_gen,name_gen,index_gen
                  FROM geneology_idx_gen gig
              ORDER BY gig.name_gen ASC";

        $stmt = $con->prepare($sql);
        $stmt->bind_result($idgen, $name, $index);
        $stmt->execute();

        $geneologyArray = array();
        while ($stmt->fetch()) {
            $geneology = new cl_geneology();

            $geneology->set_Idgen($idgen);
            $geneology->set_Name($name);
            $geneology->set_Index($index);

            array_push($geneologyArray, $geneology);
        }

        $stmt->close();
        unset($stmt);

        return $this->createJson($geneologyArray);
    }

    public function createJson($json)
    {
        $this->json = new CreateJson($json);
        return $this->json->getJson();
    }
}