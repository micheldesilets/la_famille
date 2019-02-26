<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 09:22
 */

include_once 'Creator.php';
include_once 'KyrgyzstanProduct.php';

class TextFactory extends Creator
{
    protected function factoryMethod()
    {
        $product = new TextProduct();
        return $product->getProperties();
    }
}