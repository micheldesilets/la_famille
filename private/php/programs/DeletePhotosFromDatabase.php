<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-27
 * Time: 11:36
 */

sec_session_start();

class DeletePhotosFromDatabase
{
    private $photos;
    private $validPhotos;

    public function __construct($listPhotos)
    {
        $this->photos=$listPhotos;
        $this->setValidPhotos($this->deleteMysqlPhotos());
    }

    private function setValidPhotos($validPhotos): void
    {
        $this->validPhotos = $validPhotos;
    }

    public function getValidPhotos()
    {
        return $this->validPhotos;
    }

    private function deleteMysqlPhotos()
    {
        include INCLUDES_PATH . 'db_connect.php';
        $u = PrivilegedUser::getByUsername($_SESSION["username"]);
        $user = $u->getEmail();
        $validList = array();

        $sql = "DELETE FROM photos_pho
                      WHERE photos_pho.id_pho = ?
                        AND photos_pho.owner_pho = ?";
        $stmt = $con->prepare($sql);

        foreach ($this->photos as $value) {
            $pid = $value->get_idpho();
            $stmt->bind_param("is", $pid, $user);
            $stmt->execute();
            $stmt->fetch();
            if ($stmt->affected_rows === 1) {
                array_push($validList, $value);
            }
        }
        return $validList;
    }
}