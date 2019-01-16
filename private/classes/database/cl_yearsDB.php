<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-11-28
 * Time: 11:31
 */

require_once CLASSES_PATH . '/business/cl_year.php';

class cl_yearsDB
{
    public function getAllYears()
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
            $stmt->bind_result($idyea, $iddeca, $decade, $year);

            $yearArray = array();

            while ($stmt->fetch()) {

                $yearUnit = new cl_year();

                $yearUnit->set_Idyea($idyea);
                $yearUnit->set_Iddeca($iddeca);
                $yearUnit->set_Decade($decade);
                $yearUnit->set_Year($year);

                array_push($yearArray, $yearUnit);
            }

            $stmt->close();
            unset($stmt);

            $json = createJson($yearArray);
            echo $json;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }

    public function getDecades()
    {
        require_once CLASSES_PATH . '/business/cl_decade.php';
        include INCLUDES_PATH . 'db_connect.php';

        try {
            $sql = "SELECT id_deca,decade_deca,fromYear_deca,toYear_deca
                      FROM decade_deca dd
                  ORDER BY dd.decade_deca";

            $stmt = $con->prepare($sql);
            $stmt->bind_result($iddecaR, $decadeR, $fromYearR, $toYearR);
            $stmt->execute();

            $decadeArray = [];
            while ($stmt->fetch()) {
                $decade = new decade();

                $decade->set_Iddeca($iddecaR);
                $decade->set_Decade($decadeR);
                $decade->set_FromYear($fromYearR);
                $decade->set_ToYear($toYearR);

                array_push($decadeArray, $decade);
            }

            $stmt->close();
            unset($stmt);

            $json = createJson($decadeArray);
            echo $json;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit;
        }
    }

    public
    function getYearsSelected($decade)
    {
        try {
            include INCLUDES_PATH . 'db_connect.php';

            $sql = "SELECT id_yea,iddeca_yea,decade_deca,year_yea
                     FROM year_yea yy
                          JOIN decade_deca dd
                            ON dd.id_deca = yy.iddeca_yea
                    WHERE yy.iddeca_yea = ?
                 ORDER BY yy.year_yea";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $decade);
            $stmt->bind_result($idyeaR, $iddecaR, $decadeR, $yearR);
            $stmt->execute();

            $yearArray = [];
            while ($stmt->fetch()) {
                $year = new cl_year();

                $year->set_Idyea($idyeaR);
                $year->set_Iddeca($iddecaR);
                $year->set_Decade($decadeR);
                $year->set_Year($yearR);

                array_push($yearArray, $year);
            }

            $stmt->close();
            unset($stmt);

            $json = createJson($yearArray);
            echo $json;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit;
        }
    }
}

function createJson($rawData)
{
    header("Content-Type: application/json");
    $json = json_encode($rawData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        $json = json_encode(array("jsonError", json_last_error_msg()));
        if ($json === false) {
            $json = '{"jsonError": "unknown"}';
        }
        http_response_code(500);
    }
    return $json;
}