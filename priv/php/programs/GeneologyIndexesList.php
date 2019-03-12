<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-24
 * Time: 14:55
 */

namespace priv\php\programs;

use priv\php\connection as con;

class GeneologyIndexesList
{
    private $connection;

    public function buildIdxList($idxs)
    {
        $this->connection = new con\DbConnection();
        $con = $this->connection->Connect();

        $idxList = "";
        $list = "";
        $array = explode(',', $idxs);
        try {
            $sql = "SELECT index_gen
                      FROM geneology_idx_gen gen
                     WHERE gen.id_gen = ?";

            $stmt1 = $con->prepare($sql);

            foreach ($array as $value) {
                $stmt1->bind_param("i", $value);
                $stmt1->execute();
                $data = $stmt1->get_result()->fetch_all();

                $n = $data[0];

                if ($idxList == "") {
                    $idxList = strval($n[0]);
                } else {
                    $idxList = $idxList . ',' . strval($n[0]);
                }
            }
            return $idxList;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }

}