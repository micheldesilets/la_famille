<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-01-15
 * Time: 10:57
 */

class role
{
    protected $permissions;

    protected function __construct()
    {
        $this->permissions = array();
    }

    // return a role object with associated permissions
    public static function getRolePerms($role_id)
    {
        include INCLUDES_PATH . 'db_connect.php';
        $role = new Role();
        $sql = "SELECT t2.perm_desc FROM role_perm as t1
                  JOIN permissions as t2 
                    ON t1.perm_id = t2.perm_id
                 WHERE t1.role_id = :role_id";
        $sth = $con->prepare($sql);
        $sth->execute(array(":role_id" => $role_id));

        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $role->permissions[$row["perm_desc"]] = true;
        }
        return $role;
    }

    // check if a permission is set
    public function hasPerm($permission)
    {
        return isset($this->permissions[$permission]);
    }
}