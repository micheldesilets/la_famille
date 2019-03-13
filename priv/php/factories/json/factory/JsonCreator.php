<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 09:18
 */

namespace priv\php\factories\json\factory;

use priv\php\factories\json\products as product;
use priv\php\factories\json\factory as factory;

include_once PROJECT_PATH . '/Autoload.php';

abstract class JsonCreator
{
    protected abstract function factoryMethod(factory\JsonProduct $product);

    public function doFactory($prod, $param)
    {
        switch ($prod) {
            case 1:
                $jsonProduct = new product\GetPhotosProduct ($param);
                break;
            case 2:
                $jsonProduct = new product\GetAllYearsProduct($param);
                break;
            case 3:
                $jsonProduct = new product\GetObjectsProduct($param);
                break;
            case 4:
                $jsonProduct = new product\GetReadingsProduct($param);
                break;
            case 5:
                $jsonProduct = new product\GetSelectedDownloadPhotosProduct($param);
                break;
            case 6:
                $jsonProduct = new product\GetPhotoMetadataProduct($param);
                break;
            case 7:
                $jsonProduct = new product\GetMainFolderProduct($param);
                break;
            case 8:
                $jsonProduct = new product\GetFoldersTreeProduct($param);
                break;
            case 9:
                $jsonProduct = new product\GetShiftingFoldersProduct($param);
                break;
            case 10:
                $jsonProduct = new product\GetGeneologyListProduct($param);
                break;
            case 11:
                $jsonProduct = new product\GetSearchedPhotosProduct($param);
                break;
            case 12:
                $jsonProduct = new product\GetFolderLevelOneProduct($param);
                break;
            case 13:
                $jsonProduct = new product\GetFolderLevelTwoProduct($param);
                break;
            case 14:
                $jsonProduct = new product\GetFolderLevelThreeProduct($param);
                break;
        }
        $mfg = $this->factoryMethod($jsonProduct);
        return $mfg;
    }
}
