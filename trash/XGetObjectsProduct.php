<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-26
 * Time: 08:34
 */

include_once 'XJsonProduct.php';

class GetObjectsProduct implements JsonProduct
{
    private $path;
    private $json;
    private $param;

    public function __construct($param)
    {
        $this->param=$param;
    }

    public function getProperties($param)
    {
        include INCLUDES_PATH . 'db_connect.php';

        try {
            $sql = "SELECT description_obj,preview_pfo,file_obj
                      FROM objects_obj obj
                           JOIN photos_folders_pfo pfo
                             ON pfo.idfol_pfo = obj.idfol_obj
                     WHERE pfo.idfol_pfo = ?
                  ORDER BY order_obj";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $this->param);
            $stmt->bind_result($description, $preview, $file);
            $stmt->execute();

            $objectArray = array();
            while ($stmt->fetch()) {
                $object = new Objects();

                $object->set_description($description);
                $object->set_File($preview . $file);
                array_push($objectArray, $object);
            }

            $stmt->close();
            unset($stmt);

            $this->json = new CreateJson($objectArray);
            return $this->json->getJson();

        } catch (Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }
}