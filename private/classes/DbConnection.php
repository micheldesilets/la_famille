<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-16
 * Time: 11:06
 */

include_once CLASSES_PATH . '/DbConParams.php';

class DbConnection
{
    private $params;
    private $host;
    private $user;
    private $password;
    private $dbName;

    public function __construct()
    {
        $this->params = new DbConParams();
        $this->host = $this->params->getHost();
        $this->user = $this->params->getUser();
        $this->password = $this->params->getPassword();
        $this->dbName = $this->params->getDbName();
    }

    public function Connect()
    {
        try {
            $con = new mysqli($this->host, $this->user, $this->password,
                $this->dbName);
            return $con;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit('Error connecting to database');
        }
    }
}