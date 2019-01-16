<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-21
 * Time: 10:54
 */

class readingsDB
{
    public function getReadings($path)
    {
        include INCLUDES_PATH . 'db_connect.php';
        require_once CLASSES_PATH . '/business/cl_readings.php';

        try {
            $sql = "SELECT title_rea,address_rea,intro_rea,summary_rea,full_pfo,
                           file_rea
                      FROM readings_rea rr
                           JOIN photos_folders_pfo pfp
                             ON pfp.idfol_pfo = rr.idfol_rea
                     WHERE pfp.idfol_pfo = ?
                     ORDER BY rr.order_rea";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $path);
            $stmt->bind_result($title, $address, $intro, $summary, $full, $file);
            $stmt->execute();

            $readingArray = [];
            while ($stmt->fetch()) {
                $reading = new Readings();

                $reading->set_Title($title);
                $reading->set_Address($address);
                if (!empty($intro)) {
                    $reading->set_Intro($intro);
                }
                if (!empty($summary)) {
                    $reading->set_sumary($summary);
                }
                $reading->set_File($full . $file);
                array_push($readingArray, $reading);
            }

            $stmt->close();
            unset($stmt);

            header("Content-Type: application/json");
            $json = json_encode($readingArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            if ($json === false) {
                $json = json_encode(array("jsonError", json_last_error_msg()));
                if ($json === false) {
                    $json = '{"jsonError": "unknown"}';
                }
                http_response_code(500);
            }
            echo $json;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }
}
