<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-11-28
 * Time: 11:31
 */

class cl_yearsDB
{
    public function getAllYears()
    {
        $wd = getcwd();

        require_once '../classes/business/cl_year.php';
        include '../connection/connect.php';

        $sql = "CALL getAllYears()";

        if ($result = mysqli_query($con, $sql)) {
            // Return the number of rows in result set
            $rowcount = mysqli_num_rows($result);
            /* printf("Result set has % d rows . \n", $rowcount); */
        } else {
            echo("nothing");
        };

        $yearArray = array();
        $l = 1;

        while ($l <= $rowcount):
            // Associative array
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            $year = new cl_year();

            $year->set_Idyea($row["id_yea"]);
            $year->set_Decade($row["decade_deca"]);
            $year->set_Year($row["year_yea"]);

            array_push($yearArray, $year);

            $l++;
        endwhile;

// Free result set
        mysqli_free_result($result);

        mysqli_close($con);

        $json = createJson($yearArray);
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