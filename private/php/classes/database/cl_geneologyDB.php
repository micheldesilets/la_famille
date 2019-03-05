<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-11-29
 * Time: 09:52
 */

class cl_geneologyDB
{
    public function getGeneologyList()
    {
        include INCLUDES_PATH . 'db_connect.php';
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

        $json = createJson($geneologyArray);
        echo $json;
    }
}

function createJson($rawData)
{
    header("Content-Type: application/json");
    $json = json_encode($rawData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        // Avoid echo of empty string (which is invalid JSON), and
        // JSONify the error message instead:
        $json = json_encode(array("jsonError", json_last_error_msg()));
        if ($json === false) {
            // This should not happen, but we go all the way now:
            $json = '{"jsonError": "unknown"}';
        }
        // Set HTTP response status code to: 500 - Internal Server Error
        http_response_code(500);
    }
    return $json;
}