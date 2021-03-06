<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-04
 * Time: 16:04
 */

namespace priv\php\programs;

use priv\php\connection as con;

class GetUserRole
{
    private $user;
    private $connection;

    public function __construct($user)
    {
        $this->user=$user;
    }

    public function getUserRole()
    {
        $this->connection = new con\DbConnection();
        $con = $this->connection->Connect();

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

            $stmt->bind_param("s", $this->user);
            $stmt->bind_result($idRol);
            $stmt->execute();

            $stmt->fetch();
            $stmt->close();

            return $idRol;

        } catch (\Exception $e) {
            error_log($e->getMessage());
            exit;
        }
    }
}