<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-20
 * Time: 15:37
 */

class Text
{
    protected $adapter;

    public function __construct($adapter)
    {
        $this->adapter = $adapter;
    }

    public function output($data){
        return $this->adapter->output($data);
    }
}