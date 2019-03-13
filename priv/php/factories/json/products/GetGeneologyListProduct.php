<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-05
 * Time: 09:56
 */

namespace priv\php\factories\json\products;

use priv\php\{factories\json\factory as factory,connection as con,
    programs as prog,classes\business as business};

class GetGeneologyListProduct implements factory\JsonProduct
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
        $this->connection = new con\DbConnection();
        $con = $this->connection->Connect();

        $sql = "SELECT id_gen,name_gen,index_gen
                  FROM geneology_idx_gen gig
              ORDER BY gig.name_gen ASC";

        $stmt = $con->prepare($sql);
        $stmt->bind_result($idgen, $name, $index);
        $stmt->execute();

        $geneologyArray = array();
        while ($stmt->fetch()) {
            $geneology = new business\Geneology();

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
        $this->json = new prog\CreateJson($json);
        return $this->json->getJson();
    }
}