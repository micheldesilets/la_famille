<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-20
 * Time: 15:47
 */

// include_once 'TextAdapterInterface.php';

class JSONAdapter
{
    public function output($data)
    {
        return json_encode($data);
    }
}