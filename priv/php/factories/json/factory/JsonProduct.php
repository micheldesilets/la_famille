<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 09:30
 */

namespace priv\php\factories\json\factory;

interface JsonProduct
{
    function __construct($param);

    function getProperties();

    function createJson($json);
}