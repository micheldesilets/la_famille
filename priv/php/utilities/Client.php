<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-01-28
 * Time: 09:42
 */

include_once '../../priv/initialize.php';
include_once PRIVATE_PATH . '/php/MobileSniffer.php';

class Client
{
    private $mobSniff;

    public function __construct()
    {
        $this->mobSniff = new MobileSniffer();
        echo 'Device = ' . $this->mobSniff->findDevice() . '<br/>';
        echo 'Browser = ' . $this->mobSniff->findBrowser() . '<br/>';
    }
}

$trigger= new Client();