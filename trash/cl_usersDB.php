<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-01-17
 * Time: 13:30
 */

require_once CLASSES_PATH . '/business/cl_users.php';

class cl_usersDB
{
   /* public function GetMaimFolder($member)
    {
        include INCLUDES_PATH . 'db_connect.php';
        mysqli_set_charset($con, "utf8");
        try {
            $userIdRole = $this->getUserRole($member);

            $sql = "SELECT id_usr,username_usr,email_usr,idmem_usr,
                           first_name_mem
                      FROM users_usr usr
                           JOIN members_mem mem
                             ON mem.id_mem = usr.idmem_usr
                     WHERE mem.first_name_mem = ?";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("s", $member);
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

            $json = createJson($arrayAut);
            echo $json;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit;
        }
    }*/

  /*  private
    function getUserRole($user)
    {
        include INCLUDES_PATH . 'db_connect.php';
        mysqli_set_charset($con, "utf8");

        $sql = "SELECT rol.id_rol
                  FROM users_usr usr
                  JOIN user_role_uro uro
                    ON usr.id_usr = uro.idusr_uro
                  JOIN roles_rol rol
                    ON rol.id_rol = idrol_uro
                 WHERE usr.username_usr = ?";

        try {
            $stmt = $con->prepare($sql);

            $stmt->bind_param("s", $user);
            $stmt->bind_result($idRol);
            $stmt->execute();

            $stmt->fetch();
            $stmt->close();

            return $idRol;

        } catch (Exception $e) {
            error_log($e->getMessage());
            exit;
        }
    }
}

function createJson($rawData)
{
    header("Content-Type: application / json");
    $json = json_encode($rawData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        $json = json_encode(array("jsonError", json_last_error_msg()));
        if ($json === false) {
            $json = '{"jsonError": "unknown"}';
        }
        http_response_code(500);
    }
    return $json;
}*/