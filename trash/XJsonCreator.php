<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 09:18
 */

include_once 'XGetPhotosProduct.php';
include_once 'XGetAllYearsProduct.php';
include_once 'XGetObjectsProduct.php';
include_once 'XGetReadingsProduct.php';

abstract class JsonCreator
{
    protected abstract function factoryMethod(JsonProduct $product,$param);

    public function doFactory($prod,$param)
    {
        switch ($prod){
            case 1:
                $jsonProduct=new GetPhotosProduct($param);
                break;
            case 2:
                $jsonProduct=new GetAllYearsProduct($param);
            case 3:
                $jsonProduct=new GetObjectsProduct($param);
            case 4:
                $jsonProduct=new GetReadingsProduct($param);
        }
        $mfg = $this->factoryMethod($jsonProduct,$param);
        return $mfg;
    }
}
