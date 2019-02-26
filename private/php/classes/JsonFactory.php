<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 15:09
 */

include_once 'JsonCreator.php';
include_once 'JsonProduct.php';

class JsonFactory extends JsonCreator
{
    private $json;

    protected function factoryMethod(JsonProduct $product,$path)
    {
        $this->json = $product;
        return ($this->json->getProperties($path));
    }
}