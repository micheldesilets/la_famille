<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-21
 * Time: 10:54
 */

class objectsDB
{
    public function getObjects($path)
    {
        include INCLUDES_PATH . 'db_connect.php';
        require_once CLASSES_PATH . '/business/cl_objects.php';

        try {
            $sql = "SELECT description_obj,preview_pfo,file_obj
                      FROM objects_obj obj
                           JOIN photos_folders_pfo pfo
                             ON pfo.idfol_pfo = obj.idfol_obj
                     WHERE pfo.idfol_pfo = ?
                  ORDER BY order_obj";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $path);
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

            header("Content-Type: application/json");
            $json = json_encode($objectArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            if ($json === false) {
                $json = json_encode(array("jsonError", json_last_error_msg()));
                if ($json === false) {
                    $json = '{"jsonError": "unknown"}';
                }
                http_response_code(500);
            }
            echo $json;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }
}
