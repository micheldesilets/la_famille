<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-15
 * Time: 11:37
 */

abstract class returnJson
{


    function __construct($data)
    {
    }

    abstract protected function returnJson($data);
}