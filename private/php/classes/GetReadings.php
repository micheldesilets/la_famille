<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-26
 * Time: 08:48
 */

class GetReadings
{
    private $path;
    private $json;


    public function __construct($path)
    {
        $this->path=$path;
    }

    public function getReadings()
    {
        include INCLUDES_PATH . 'db_connect.php';

        try {
            $sql = "SELECT title_rea,address_rea,intro_rea,summary_rea,full_pfo,
                           file_rea
                      FROM readings_rea rr
                           JOIN photos_folders_pfo pfp
                             ON pfp.idfol_pfo = rr.idfol_rea
                     WHERE pfp.idfol_pfo = ?
                     ORDER BY rr.order_rea";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $this->path);
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

            $this->json = new CreateJson($readingArray);
            echo $this->json->createJsonMethod();

        } catch (Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }

}