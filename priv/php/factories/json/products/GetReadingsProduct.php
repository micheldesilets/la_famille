<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-26
 * Time: 08:48
 */

namespace priv\php\factories\json\products;

use priv\php\{factories\json\factory as factory,
    connection as con,
    programs as prog,
    classes\business as business};

class GetReadingsProduct implements factory\JsonProduct
{
    private $json;
    private $param;
    private $connection;
    private $con;

    public function __construct($param)
    {
        $this->setParam($param);
        $this->connection = new con\DbConnection();
        $this->setCon($this->connection->Connect());
    }

    public function setCon(\mysqli $con): void
    {
        $this->con = $con;
    }

    public function getCon(): \mysqli
    {
        return $this->con;
    }

    public function setParam($param): void
    {
        $this->param = $param;
    }

    public function getParam()
    {
        return $this->param;
    }

    public function getProperties()
    {
        try {
            $sql = "SELECT title_rea,address_rea,intro_rea,summary_rea,full_rfo,
                           file_rea
                      FROM readings_rea rr
                           JOIN readings_folders_rfo rfo
                             ON rfo.idrol_rfo = rr.idrol_rea
                     WHERE rfo.idrol_rfo = ?
                     ORDER BY rr.order_rea";

            $stmt = $this->getCon()->prepare($sql);
            $ref = $this->getParam();
            $stmt->bind_param("s", $ref);
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