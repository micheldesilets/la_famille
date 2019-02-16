<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-02
 * Time: 14:33
 */

interface IConnectInfo
{
    const HOST = 'localhost';
    const  UNAME = 'mdesilets';
    const DBNAME = 'lesnormandeaudesilets_dev';
    const PW = 'ehf4EaQ_CU(N';

    function testConnection();
}