<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-26
 * Time: 08:48
 */

namespace priv\php\factories\json\products;

use priv\php\factories\json\factory as factory;
use priv\php\connection as con;
use priv\php\programs as prog;
use priv\php\classes\business as business;

class GetReadingsProduct implements factory\JsonProduct
{
    private $json;
    private $param;
    private $connection;

    public function __construct($param)
    {
        $this->param=$param;
    }

    public function getProperties()
    {
        $this->connection = new con\DbConnection();
        $con = $this->connection->Connect();

        try {
            $sql = "SELECT title_rea,address_rea,intro_rea,summary_rea,full_pfo,
                           file_rea
                      FROM readings_rea rr
                           JOIN photos_folders_pfo pfp
                             ON pfp.idfol_pfo = rr.idfol_rea
                     WHERE pfp.idfol_pfo = ?
                     ORDER BY rr.order_rea";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $this->param);
            $stmt->bind_result($title, $address, $intro, $summary, $full, $file);
            $stmt->execute();

            $readingArray = [];
            while ($stmt->fetch()) {
                $reading = new business\Readings();

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

            return $this->createJson($readingArray);

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