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
    public function GetUsers($user)
    {
        include INCLUDES_PATH . 'db_connect.php';
        mysqli_set_charset($con,"utf8");
        try {
            $userIdRole = $this->getUserRole($user);

            $sql = "SELECT id_usr,username_usr,email_usr
                      FROM users_usr";

            $stmt = $con->prepare($sql);
            $stmt->bind_result($idusr, $username, $email);
            $stmt->execute();

            $arrayAut = [];
            while ($stmt->fetch()) {
                if ($userIdRole === 1):
                    $usr = new users($username, $email);
                    $usr->set_UserId($idusr);
                    $usr->set_Permission("");
                    $usr->set_Password("");
                    $usr->set_Roles("");
                    array_push($arrayAut, $usr);
                elseif ($username === $user):
                    $usr = new users($username, $email);
                    $usr->set_UserId($idusr);
                    $usr->set_Permission("");
                    $usr->set_Password("");
                    $usr->set_Roles("");
                    array_push($arrayAut, $usr);
                endif;
            }

            $json = createJson($arrayAut);
            echo $json;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit;
        }
    }

    private
    function getUserRole($user)
    {
        include INCLUDES_PATH . 'db_connect.php';
        mysqli_set_charset($con,"utf8");

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
}