<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 09:27
 */

include_once 'Creator.php';
include_once 'GraphicProduct.php';

class GraphicFactory extends Creator
{
    protected function factoryMethod()
    {
        $product = new GraphicProduct();
        return $product->getProperties();
    }
}