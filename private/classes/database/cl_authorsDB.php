<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-01-17
 * Time: 13:30
 */

require_once CLASSES_PATH . '/business/cl_authors.php';

class cl_authorsDB
{
    public function GetAuthors()
    {
        include INCLUDES_PATH . 'db_connect.php';
        try {
            $sql = "SELECT id_aut,first_name_aut,last_name_aut,prefix_aut
                    FROM author_aut";

            $stmt = $con->prepare($sql);
            $stmt->bind_result($idaut, $firstName, $$lastName, $prefix);
            $stmt->execute();

            $arrayAut = [];
            while ($stmt->fetch()) {
                $aut = new authors($idaut, $firstName, $$lastName, $prefix);
                array_push($arrayAut, $aut);
            }
            $json = createJson($arrayAut);
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