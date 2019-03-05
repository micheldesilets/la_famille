<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-02
 * Time: 14:41
 */
include_once CLASSES_PATH . 'IConnectInfo.php';

class connectSQL implements IConnectInfo
{
    private $server = IConnectInfo::HOST;
    private $currentDB = IConnectInfo::DBNAME;
    private $user = IConnectInfo::UNAME;
    private $pass = IConnectInfo::PW;

    public function testConnection()
    {
        try {
            $mysqli = new mysqli($this->server, $this->user, $this->user,
                $this->pass);
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit('Error connecting to database');
        }
    }
}