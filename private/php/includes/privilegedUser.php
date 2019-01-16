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

    public function __construct()
    {
        parent::__construct();
    }

    // override User method
    public static function getByUsername($username)
    {
        include INCLUDES_PATH . 'db_connect.php';
        try {
            $sql = "SELECT id,username
                      FROM members 
                     WHERE username = ?";

            $sth = $con->prepare($sql);
            $sth->bind_param("s", $username);
            $sth->bind_result($idR,$usernameR);
            $sth->execute();
            mysqli_fetch_all($result,MYSQLI_ASSOC);

            while ($sth->fetch()) {
                $privUser = new privilegedUser();
                $privUser->user_id = $result[0]["user_id"];
                $privUser->username = $username;
                /*            $privUser->password = $result[0]["password"];
                            $privUser->email_addr = $result[0]["email_addr"];
                            $privUser->initRoles();*/
            }
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
        $sql = "SELECT t1.role_id, t2.role_name 
                  FROM user_role as t1
                       JOIN roles as t2 ON t1.role_id = t2.role_id
                 WHERE t1.user_id = :user_id";

        $sth = $con->prepare($sql);
        $sth->execute(array(":user_id" => $this->user_id));

        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $this->roles[$row["role_name"]] = Role::getRolePerms($row["role_id"]);
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
}