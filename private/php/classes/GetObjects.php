<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-26
 * Time: 08:34
 */

class GetObjects
{
    private $path;
    private $json;

    public function __construct($path)
    {
        $this->path=$path;
    }

    public function getObjects()
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
            $stmt->bind_param("i", $this->path);
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
            echo $this->json->createJsonMethod();

        } catch (Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }
}