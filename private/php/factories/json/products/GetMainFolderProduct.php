<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-04
 * Time: 15:09
 */

include_once PRIVATE_PHP_PATH . '/factories/json/factory/JsonProduct.php';
include_once PRIVATE_PHP_PATH . '/misc/GetUserRole.php';

class GetMainFolderProduct implements JsonProduct
{
    private $param;
    private $json;
    private $userIdRole;
    private $role;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function getProperties()
    {
        include INCLUDES_PATH . 'db_connect.php';
        mysqli_set_charset($con, "utf8");
        try {

            $this->userIdRole = new GetUserRole($this->param);
            $this->role=$this->userIdRole->getUserRole();

            $sql = "SELECT id_usr,username_usr,email_usr,idmem_usr,
                           first_name_mem
                      FROM users_usr usr
                           JOIN members_mem mem
                             ON mem.id_mem = usr.idmem_usr
                     WHERE mem.first_name_mem = ?";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("s", $this->param);
            $stmt->bind_result($idusr, $username, $email, $idmem, $firstName);
            $stmt->execute();
            $stmt->fetch();

            $arrayAut = [];
            $usr = new users($username, $email);
            $usr->set_UserId($idusr);
            $usr->set_Permission("");
            $usr->set_Password("");
            $usr->set_Roles("");
            $usr->set_Idmem($idmem);
            $usr->set_Firstname($firstName);
            array_push($arrayAut, $usr);

            return $this->createJson($arrayAut);

        } catch (Exception $e) {
            error_log($e->getMessage());
            exit;
        }
    }

    public function createJson($json)
    {
        $this->json = new CreateJson($json);
        return $this->json->getJson();
    }
}