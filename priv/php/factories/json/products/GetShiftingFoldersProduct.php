<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-05
 * Time: 09:31
 */

namespace priv\php\factories\json\products;

use priv\php\factories\json\factory as factory;
use priv\php\connection as con;
use priv\php\programs as prog;
use priv\php\classes\business as business;

class GetShiftingFoldersProduct implements factory\JsonProduct
{
    private $param;
    private $json;
    private $connection;

    public function __construct($param)
    {
    $this->param=$param;
    }

    public function getProperties()
    {
        try {
            $this->connection = new con\DbConnection();
            $con = $this->connection->Connect();

            $folder = new business\folders();

            $sql = "SELECT fol.id_fol, tt.type_typ, mem.first_name_mem, 
                           dd.decade_deca, yy.year_yea, fol.title_fol
                      FROM folders_fol fol
                           JOIN members_mem mem
                             ON fol.idmem_fol = mem.id_mem
                           JOIN type_typ tt
                             ON fol.idtyp_fol = tt.id_typ
                           JOIN decade_deca dd
                             ON fol.iddec_fol = dd.id_deca
                           JOIN year_yea yy
                             ON fol.idyea_fol = yy.id_yea
                  ORDER BY mem.first_name_mem, tt.type_typ, dd.decade_deca, 
                           yy.year_yea, fol.title_fol";

            $stmt = $con->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($idfol, $idtyp, $firstname,
                $decade, $year, $title);

            $folderArray = array();

            while ($stmt->fetch()) {
                $folder = new business\folders();

                $folder->setFolderId(strval($idfol));
                $folder->setTypeId("2");
                $folder->setMember("");
                $folder->setDecade("");
                $folder->setYear("");
                $folder->setTitle($title);
                $folder->setLevels("");

                array_push($folderArray, $folder);
            }

            $stmt->close();
            unset($conn);
            unset($stmt);

            return $this->createJson($folderArray);

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