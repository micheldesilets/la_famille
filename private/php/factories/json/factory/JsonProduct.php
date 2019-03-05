<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 09:30
 */

interface JsonProduct
{
    function __construct($param);

    function getProperties();

    function createJson($json);
}