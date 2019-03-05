<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-28
 * Time: 08:03
 */

include_once 'XJsonProduct.php';

class GetAllYearsProduct implements JsonProduct
{
    private $jsonString;
    private $json;
    private $param;

    public function __construct($param)
    {
        $this->param=$param;
    }

    public function getProperties($param)
    {
        try {
            include INCLUDES_PATH . 'db_connect.php';

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
                $yearUnit = new cl_year($idyeaR, $iddecaR, $decadeR, $yearR);
                array_push($yearArray, $yearUnit);
            }

            $stmt->close();
            unset($stmt);

            $this->json = new CreateJson($yearArray);
            return $this->json->getJson();
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }
}