<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 15:09
 */

include_once PRIVATE_PHP_PATH .  '/factories/json/factory/JsonCreator.php';
include_once PRIVATE_PHP_PATH .  '/factories/json/factory/JsonProduct.php';

class JsonFactory extends JsonCreator
{
    private $json;

    protected function factoryMethod(JsonProduct $product)
    {
        $this->json = $product;
        return ($this->json->getProperties());
    }
}