<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-05
 * Time: 08:45
 */

namespace priv\php\factories\json\products;

use priv\php\{factories\json\factory as factory,
    connection as con,
    programs as prog,
    classes\business as business};

class GetNewFoldersTreeProduct implements factory\JsonProduct
{
    private $param = 2;
    private $json;
    private $connection;

    public function __construct($param)
    {

    }

    public function getProperties()
    {
        try {
            $this->connection = new con\DbConnection();
            $con = $this->connection->Connect();

            $sql = "SELECT fco.id_fco, mem.first_name_mem,
                           fo1.name_fo1,fo2.name_fo2,fo3.name_fo3
                      FROM folders_concat_fco fco
                           INNER JOIN members_mem mem
                                   ON fco.idmem_fco = mem.id_mem
                      LEFT OUTER JOIN folders_one_fo1 fo1
                                   ON fco.id_fo1_fco = fo1.id_fo1
                      LEFT OUTER JOIN folders_two_fo2 fo2
                                   ON fco.id_fo2_fco = fo2.id_fo2
                      LEFT OUTER JOIN folders_three_fo3 fo3
                                   ON fco.id_fo3_fco = fo3.id_fo3      
                      ORDER BY binary mem.first_name_mem desc, fo1.name_fo1,
                                      fo1.name_fo1,fo1.name_fo1";

            $stmt = $con->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($idfco, $firstname,
                $fo1Name, $fo2Name, $fo3Name);

            $folderArray = array();
            while ($stmt->fetch()) {
                $folder = new business\folders();

                $folder->setFolderId(strval($idfco));
                $folder->setTypeId(strval(''));
                $folder->setMember($firstname);
                $folder->setDecade(strval($fo1Name));
                $folder->setYear(strval($fo2Name));
                $folder->setTitle($fo3Name);
                $folder->setLevels(strval(''));

                array_push($folderArray, $folder);
            }

            return $this->createJson($folderArray);

        } catch (\Exception $e) {
            error_log($e->getMessage());
            exit();
        } finally {
            $stmt->close();
        }
    }

    public function createJson($json)
    {
        $this->json = new prog\CreateJson($json);
        return $this->json->getJson();
    }
}