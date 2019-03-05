<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 09:18
 */

include_once PRIVATE_PHP_PATH . '/factories/json/products/GetPhotosProduct.php';
include_once PRIVATE_PHP_PATH . '/factories/json/products/GetAllYearsProduct.php';
include_once PRIVATE_PHP_PATH . '/factories/json/products/GetObjectsProduct.php';
include_once PRIVATE_PHP_PATH . '/factories/json/products/GetReadingsProduct.php';
include_once PRIVATE_PHP_PATH . '/factories/json/products/GetSelectedDownloadPhotosProduct.php';
include_once PRIVATE_PHP_PATH . '/factories/json/products/GetPhotoMetadataProduct.php';
include_once PRIVATE_PHP_PATH . '/factories/json/products/GetMainFolderProduct.php';
include_once PRIVATE_PHP_PATH . '/factories/json/products/GetFoldersTreeProduct.php';
include_once PRIVATE_PHP_PATH . '/factories/json/products/GetShiftingFoldersProduct.php';
include_once PRIVATE_PHP_PATH . '/factories/json/products/GetGeneologyListProduct.php';

abstract class JsonCreator
{
    protected abstract function factoryMethod(JsonProduct $product);

    public function doFactory($prod, $param)
    {
        switch ($prod) {
            case 1:
                $jsonProduct = new GetPhotosProduct($param);
                break;
            case 2:
                $jsonProduct = new GetAllYearsProduct($param);
                break;
            case 3:
                $jsonProduct = new GetObjectsProduct($param);
                break;
            case 4:
                $jsonProduct = new GetReadingsProduct($param);
                break;
            case 5:
                $jsonProduct = new GetSelectedDownloadPhotosProduct($param);
                break;
            case 6:
                $jsonProduct = new GetPhotoMetadataProduct($param);
                break;
            case 7:
                $jsonProduct = new GetMainFolderProduct($param);
                break;
            case 8:
                $jsonProduct = new GetFoldersTreeProduct($param);
                break;
            case 9:
                $jsonProduct = new GetShiftingFoldersProduct($param);
                break;
            case 10:
                $jsonProduct = new GetGeneologyListProduct($param);
                break;
        }
        $mfg = $this->factoryMethod($jsonProduct);
        return $mfg;
    }
}
