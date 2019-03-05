<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-16
 * Time: 10:08
 */

class DbConParams
{
    private $host;
    private $user;
    private $password;
    private $database;
    private $canRegister;
    private $secure;

    function __construct()
    {
        $this->setHost("localhost");
        $this->setUser("mdesilets");
        $this->setPassword("ehf4EaQ_CU(N");
        $this->setDatabase("lesnormandeaudesilets_DEV");
        $this->canRegister = "any";
        $this->secure = False;
    }

    public function setHost($setValue)
    {
        $this->host = $setValue;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setDatabase($database)
    {
        $this->database = $database;
    }

    public function getDbName()
    {
        return $this->database;
    }
}
