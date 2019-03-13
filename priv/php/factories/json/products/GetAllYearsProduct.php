<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-28
 * Time: 08:03
 */

namespace priv\php\factories\json\products;

use priv\php\{factories\json\factory as factory,connection as con,
    programs as prog,classes\business as business};

class GetAllYearsProduct implements factory\JsonProduct
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
        try {
            $this->connection = new con\DbConnection();
            $con = $this->connection->Connect();

            $sql = "SELECT id_yea,iddeca_yea,decade_deca,year_yea
                      FROM year_yea yy
                           JOIN decade_deca dd
                             ON dd.id_deca = yy.iddeca_yea
                     ORDER BY dd.decade_deca, yy.year_yea";

            $stmt = $con->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($idyeaR, $iddecaR, $decadeR, $yearR);

            $yearArray = array();

            while ($stmt->fetch()) {
                $yearUnit = new business\Year($idyeaR, $iddecaR, $decadeR,
                    $yearR);
                array_push($yearArray, $yearUnit);
            }

            $stmt->close();
            unset($stmt);

            return $this->createJson($yearArray);

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