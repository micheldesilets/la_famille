<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 09:30
 */

interface JsonProduct
{
    public function __construct($param);

    public function getProperties($param);
}