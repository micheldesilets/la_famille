<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 09:18
 */
include_once 'GetPhotosProduct.php';

abstract class JsonCreator
{
    protected abstract function factoryMethod(JsonProduct $product,$path);

    public function doFactory($prod,$path)
    {
        switch ($prod){
            case 1:
                $jsonProduct=new GetPhotosProduct();
                break;
        }
    //    $jsonProduct = $productNow;
        $mfg = $this->factoryMethod($jsonProduct,$path);
        return $mfg;
    }
}