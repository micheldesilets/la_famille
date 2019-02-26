<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 15:09
 */

include_once 'Creator.php';
include_once 'Product.php';

class CountryFactory extends Creator
{
    private $country;

    protected function factoryMethod(Product $product)
    {
        $this->country = $product;
        return ($this->country->getProperties());
    }
}