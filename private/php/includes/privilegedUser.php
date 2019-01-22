<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-01-15
 * Time: 11:02
 */

class privilegedUser
{
    private $roles;
    private $user_id;
    private $username;
    private $email_addr;
    private $password;

    // override User method
    public static function getByUsername($username)
    {
        include INCLUDES_PATH . 'db_connect.php';
        mysqli_set_charset($con,"utf8");
          try {
            $sql = "SELECT id_usr,username_usr,password_usr,email_usr
                      FROM users_usr 
                     WHERE username_usr = ?";

            $sth = $con->prepare($sql);
            $sth->bind_param("s", $username);
            $sth->bind_result($idR, $usernameR, $passwR, $emailR);
            $sth->execute();
            $sth->fetch();

            $privUser = new PrivilegedUser();
            $privUser->user_id = $idR;
            $privUser->username = $usernameR;
            $privUser->password = $passwR;
            $privUser->email_addr = $emailR;
            $privUser->initRoles();

            return $privUser;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit;
        }
    }

    // populate roles with their associated permissions
    protected function initRoles()
    {
        include INCLUDES_PATH . 'db_connect.php';

        $this->roles = array();
        $sql = "SELECT t1.idrol_uro, t2.name_rol 
                  FROM user_role_uro as t1
                       JOIN roles_rol as t2 ON t1.idrol_uro = t2.id_rol
                 WHERE t1.idusr_uro = ?";

        $sth = $con->prepare($sql);
        $idUsr = $this->user_id;
        $sth->bind_param("i", $idUsr);
        $sth->bind_result($idrolR, $nameR);
        $sth->execute();

        while ($sth->fetch()) {
            $this->roles[$nameR] = Role::getRolePerms($idrolR);
        }
    }

    // check if user has a specific privilege
    public function hasPrivilege($perm)
    {
        foreach ($this->roles as $role) {
            if ($role->hasPerm($perm)) {
                return true;
            }
        }
        return false;
    }

    // check if a user has a specific role
    public function hasRole($role_name) {
        return isset($this->roles[$role_name]);
    }

// insert a new role permission association
    public static function insertPerm($role_id, $perm_id) {
        $sql = "INSERT INTO role_perm (role_id, perm_id) VALUES (:role_id, :perm_id)";
        $sth = $GLOBALS["DB"]->prepare($sql);
        return $sth->execute(array(":role_id" => $role_id, ":perm_id" => $perm_id));
    }

// delete ALL role permissions
    public static function deletePerms() {
        $sql = "TRUNCATE role_perm";
        $sth = $GLOBALS["DB"]->prepare($sql);
        return $sth->execute();
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }
    public function getEmail(){
        return $this->email_addr;
    }
}