<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-05
 * Time: 08:45
 */

include_once PRIVATE_PHP_PATH . '/factories/json/factory/JsonProduct.php';

class GetFoldersTreeProduct implements JsonProduct
{
    private $param;
    private $json;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function getProperties($param)
    {
        // TODO: Implement getProperties() method.
    }

    public function createJson($json)
    {
        // TODO: Implement createJson() method.
    }
}