<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 09:37
 */

include_once 'Product.php';

class GraphicProduct implements Product
{
    private $mfgProduct;

    public function getProperties()
    {
        $this->mfgProduct = 'This is a graphic';
        return $this->mfgProduct;
    }
}