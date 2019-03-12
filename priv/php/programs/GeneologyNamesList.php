<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-24
 * Time: 14:49
 */

namespace priv\php\programs;

use priv\php\connection as con;

class GeneologyNamesList
{
    private $connection;

    public function buildNamesList($idxs)
    {
        $this->connection = new con\DbConnection();
        $con = $this->connection->Connect();

        $namesList = "";
        $array = explode(',', $idxs);

        $sql = "SELECT name_gen
                  FROM geneology_idx_gen gen
                 WHERE gen.id_gen = ?";
        $stmt = $con->prepare($sql);
        try {
            foreach ($array as $value) {
                $stmt->bind_param("s", $value);
                $stmt->execute();
                $data = $stmt->get_result()->fetch_all();

                $n = $data[0];

                if ($namesList === "") {
                    $namesList = $n[0];
                } else {
                    $namesList = $namesList . ',' . $n[0];
                }
            }
            return $namesList;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }
}