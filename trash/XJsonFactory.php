<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 15:09
 */

include_once 'XJsonCreator.php';
include_once 'XJsonProduct.php';

class JsonFactory extends JsonCreator
{
    private $json;

    protected function factoryMethod(JsonProduct $product,$param)
    {
        $this->json = $product;
        return ($this->json->getProperties($param));
    }
}