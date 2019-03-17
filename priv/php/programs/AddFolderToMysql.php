<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-13
 * Time: 10:57
 */

namespace priv\php\programs;

use priv\php\connection\DbConnection;

class AddFolderToMysql
{
    private $param;
    private $connection;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function addFolder()
    {
        $curr = getcwd();
        $this->connection = new DbConnection();
        $con = $this->connection->Connect();
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $level0Id = $this->param[0];
        $level1Id = $this->param[2];
        $level1Name = $this->param[3];
        $level2Id = $this->param[4];
        $level2Name = $this->param[5];
        $level3Name = $this->param[6];

        try {
            if ($level1Id === "0") {
                $sql = "INSERT INTO folders_one_fo1 (
                                idmem_fo1, name_fo1)
                         VALUES (?,?)";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("is", $level0Id, $level1Name);
                $stmt->execute();
                $stmt->close();
            }

            if (@$level2Name === "") {
                return;
            }

            if ($level2Id === "0") {
                $sql = "INSERT INTO folders_two_fo2 (
                                idfo1_fo2, name_fo2)
                         VALUES (?,?)";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("is", $level1Id, $level2Name);
                $stmt->execute();
                $stmt->close();
            }

            if ($level3Name === "") {
                return;
            }

            if ($level3Name != "") {
                $sql = "INSERT INTO folders_three_fo3 (
                                idfo2_fo3, name_fo3)
                         VALUES (?,?)";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("is", $level2Id, $level3Name);
                $stmt->execute();
                $stmt->close();
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }
}