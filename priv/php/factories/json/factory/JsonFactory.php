<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 15:09
 */

namespace priv\php\factories\json\factory;

use priv\php\factories\json\factory as factory;

class JsonFactory extends factory\JsonCreator
{
    private $json;

    protected function factoryMethod(factory\JsonProduct $product)
    {
        $this->json = $product;
        return ($this->json->getProperties());
    }
}