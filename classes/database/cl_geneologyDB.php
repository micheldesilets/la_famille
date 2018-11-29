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
        include_once '../connection/connect.php';
        require_once '../classes/business/cl_geneology.php';

        $sql = "CALL getGeneologyList()";

        if ($result = mysqli_query($con, $sql)) {
            // Return the number of rows in result set
            $rowcount = mysqli_num_rows($result);
            /* printf("Result set has %d rows.\n", $rowcount); */
        } else {
            echo("nothing");
        };


        $geneologyArray = array();
        $l = 1;

        while ($l <= $rowcount):
            // Associative array
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            $geneology = new cl_geneology();

            $geneology->set_Idgen($row["id_gen"]);
            $geneology->set_Name($row["name_gen"]);
            $geneology->set_Index($row["index_gen"]);

            array_push($geneologyArray, $geneology);

            $l++;
        endwhile;

// Free result set
        mysqli_free_result($result);

        mysqli_close($con);

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