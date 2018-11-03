<?php

/**
 * Created by Michel Desilets.
 * Date: 2018-10-19
 * Time: 10:04
 */
class repository
{

    function addRepository($repositData)
    {
        // current directory
        $wd = getcwd();
        require_once '../classes/business/cl_repository.php';
        include '../connection/connect.php';

        $type = $repositData[0];
        $author = $repositData[1];
        $decade = $repositData[2];
        $year = $repositData[3];
        $title = $repositData[4];
        $levels = $repositData[5];

        $repositArray = array();
        $repository = new cl_repository();

        $sql = "SELECT typ.type_typ 
            FROM type_typ typ 
            WHERE typ.id_typ = $type
            UNION ALL
            SELECT aut.first_name_aut 
            FROM author_aut aut 
            WHERE aut.id_aut = $author
            UNION ALL 
            SELECT decade_deca
            FROM decade_deca deca
            WHERE deca.id_deca = $decade
            UNION ALL 
            SELECT year_yea
            FROM year_yea yea
            WHERE yea.id_yea = $year";

        if ($result = mysqli_query($con, $sql)) {
        } else {
            echo("nothing");
        };

        $repository->set_Type(mysqli_fetch_array($result, MYSQLI_ASSOC));
        $repository->set_Author(mysqli_fetch_array($result, MYSQLI_ASSOC));
        $repository->set_Decade(mysqli_fetch_array($result, MYSQLI_ASSOC));
        $repository->set_Year(mysqli_fetch_array($result, MYSQLI_ASSOC));
        $repository->set_Title($title);
        $repository->set_Levels($levels);

        mysqli_close($con);

        $this->createRepositoryFolder($repository);

        return;
    }

    /**
     * @param $repository
     */
    function createRepositoryFolder($repository)
    {
        chdir('../');
        $curr = getcwd();
        $typePhoto = $repository->get_Type();
        $author = $repository->get_Author();
        $decade = $repository->get_Decade();
        $year = $repository->get_Year();
        $title = $repository->get_Title();

        $path = $curr . '/img';

        $path = $path . "/" . $typePhoto[type_typ];
        if (!file_exists($path)) {
            mkdir($path);
        };

        $path = $path . '/' . $author[type_typ];
        if (!file_exists($path)) {
            mkdir($path);
        };

        $path = $path . '/' . $decade[type_typ];
        if (!file_exists($path)) {
            mkdir($path);
        };

        $path = $path . '/' . $year[type_typ];
        if (!file_exists($path)) {
            mkdir($path);
        };

        $path = $path . '/' . $title;
        if (!file_exists($path)) {
            mkdir($path);
            chdir($path);
            mkdir('full');
            mkdir('preview');
        };
    }

    function getYearsSelected($decade)
    {
        $wd = getcwd();

        require_once '../classes/business/cl_year.php';
        include '../connection/connect.php';

        $sql = "CALL getYearsSelected($decade)";

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

    function addRepositoryMysql($repositData)
    {
        $curr = getcwd();
        include '../connection/connect.php';

        $typePhoto = $repositData[0];
        $author = $repositData[1];
        $decade = $repositData[2];
        $year = $repositData[3];
        $title = $repositData[4];
        $levels = $repositData[5];

        $sql = "CALL addRepository($typePhoto,'" . $title . "',$author,$decade,$year,$levels)";

        if (mysqli_query($con, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . " < br>" . mysqli_error($con);
        }

        mysqli_close($con);
        return;
    }

    function getReposits($year)
    {
        $wd = getcwd();

        require_once '../classes/business/cl_repository.php';
        include '../connection/connect.php';

        $sql = "CALL getRepositories($year)";

        if ($result = mysqli_query($con, $sql)) {
            // Return the number of rows in result set
            $rowcount = mysqli_num_rows($result);
        } else {
            echo("nothing");
        };

        $repositArray = array();
        $l = 1;

        while ($l <= $rowcount):
            // Associative array
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            $reposit = new cl_repository();

            $reposit->set_Idrpt($row['id_rpt']);
            $reposit->set_Title($row["title_rpt"]);

            array_push($repositArray, $reposit);

            $l++;
        endwhile;

        // Free result set
        mysqli_free_result($result);

        mysqli_close($con);

        $json = createJson($repositArray);
        echo $json;
    }

    function addMetadataToMysql($meta)
    {
        $curr = getcwd();
        include '../connection/connect.php';

        $myfile = fopen($meta, "r") or die("Unable to open file!");
        echo fread($myfile, filesize($meta));
        fclose($myfile);
    }

    function getPath($path)
    {
        $typePhoto = $path[0];
        $author = $path[1];
        $decade = $path[2];
        $year = $path[3];
        $title = $path[4];

        include '../connection/connect.php';

        $sql = "CALL getPath($typePhoto,$author,$decade,$year,$title)";

        if ($result = mysqli_query($con, $sql)) {
            // Return the number of rows in result set
            $rowcount = mysqli_num_rows($result);
        } else {
            echo("nothing");
        };

        IF ($rowcount > 0) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $current = getcwd() . "\n";
            $path = '../img/' . utf8_encode($row['type_typ']) . '/' . utf8_encode($row['first_name_aut']) . '/' . $row['decade_deca'] . '/' .
                $row['year_yea'] . '/' . utf8_encode($row['title_rpt']) . '/preview/';
            return $path;
        }
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