<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-05
 * Time: 08:45
 */

namespace priv\php\factories\json\products;

use priv\php\factories\json\factory as factory;
use priv\php\connection as con;
use priv\php\programs as prog;
use priv\php\classes\business as business;

class GetFoldersTreeProduct implements factory\JsonProduct
{
    private $param;
    private $json;
    private $connection;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function getProperties()
    {
        try {
            $this->connection = new con\DbConnection();
            $con = $this->connection->Connect();

            $sql = "SELECT fol.id_fol, typ.id_typ, mem.first_name_mem,
                           deca.decade_deca, yea.year_yea, fol.title_fol,
                           fol.levels_fol, mem.prefix_mem 
                      FROM folders_fol fol
                           INNER JOIN members_mem mem
                                   ON fol.idmem_fol = mem.id_mem
                           INNER JOIN decade_deca deca
                                   ON fol.iddec_fol = deca.id_deca
                           INNER JOIN year_yea yea
                                   ON fol.idyea_fol = yea.id_yea
                           INNER JOIN type_typ typ
                                   ON fol.idtyp_fol = typ.id_typ
                     WHERE typ.id_typ = ?
                  ORDER BY binary mem.first_name_mem desc, deca.decade_deca, 
                           yea.year_yea, fol.title_fol";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $this->param);
            $stmt->execute();
            $stmt->bind_result($idfol, $idtyp, $firstname,
                $decade, $year, $title, $levels, $prefix);

            $folderArray = array();
            while ($stmt->fetch()) {
                $folder = new business\folders();

                $folder->setFolderId(strval($idfol));
                $folder->setTypeId(strval($idtyp));
                $folder->setMember($prefix . $firstname);
                $folder->setDecade(strval($decade));
                $folder->setYear(strval($year));
                $folder->setTitle($title);
                $folder->setLevels(strval($levels));

                array_push($folderArray, $folder);
            }

            $stmt->close();

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