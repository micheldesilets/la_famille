<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-20
 * Time: 15:43
 */

class TextFactory implements Factory
{
    protected $adapter;

    public function __construct(AdapterFactory $adapter)
    {
        $this->adapter = $adapter;
    }

    public function make($config)
    {
     return new Text($this->adapter->make($config));
    }
}