<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-01-18
 * Time: 14:10
 */

class users implements JsonSerializable
{
    private $m_user_id;
    private $m_username;
    private $m_email_addr;
    private $m_password;
    private $m_roles;
    private $m_permission;

    public function __construct($username, $email)
    {
        $this->m_username = $username;
        $this->m_email_addr = $email;
    }

    /**
     * @return mixed
     */
    public function get_Username()
    {
        return $this->m_username;
    }

    /**
     * @return mixed
     */
    public function get_EmailAddr()
    {
        return $this->m_email_addr;
    }

    /**
     * @param mixed $m_password
     */
    public function set_Password($m_password): void
    {
        $this->m_password = $m_password;
    }

    /**
     * @return mixed
     */
    public function get_Password()
    {
        return $this->m_password;
    }

    /**
     * @param mixed $m_roles
     */
    public function set_Roles($m_roles): void
    {
        $this->m_roles = $m_roles;
    }

    /**
     * @return mixed
     */
    public function get_Roles()
    {
        return $this->m_roles;
    }

    /**
     * @param mixed $m_user_id
     */
    public function set_UserId($m_user_id): void
    {
        $this->m_user_id = $m_user_id;
    }

    /**
     * @return mixed
     */
    public function get_UserId()
    {
        return $this->m_user_id;
    }

    /**
     * @param mixed $m_permission
     */
    public function set_Permission($m_permission): void
    {
        $this->m_permission = $m_permission;
    }

    /**
     * @return mixed
     */
    public function get_Permission()
    {
        return $this->m_permission;
    }

    public function jsonSerialize()
    {
        return [
            'idusr' => $this->get_UserId(),
            'userName' => $this->get_Username(),
            'email' => $this->get_EmailAddr(),
            'password' => $this->get_Password(),
            'roles' => $this->get_Roles(),
            'permission' => $this->get_Permission()
        ];
    }
}