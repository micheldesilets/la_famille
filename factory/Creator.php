<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 09:18
 */

abstract class Creator
{
    protected abstract function factoryMethod(Product $product);

    public function doFactory($productNow)
    {
        $countryProduct = $productNow;
        $mfg = $this->factoryMethod($countryProduct);
        return $mfg;
    }
}