<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-20
 * Time: 09:37
 */


include_once 'Product.php';
include_once 'FormatHelper.php';

class MoldovaProduct implements Product
{
    private $mfgProduct;
    private $formatHelper;
    private $countryNow;

    public function getProperties()
    {
        // Load text from external file
        $this->countryNow = file_get_contents('Moldova.txt');

        $this->formatHelper = new FormatHelper();
        $this->formatHelper = $this->formatHelper->addTop();
        $this->mfgProduct .= "<img src='1974_Explosion_mont_Wright_-_11.jpg' class='pixRight' width='208' 
           height='450'>";
        $this->mfgProduct .= "<header>Moldova</header>";
        $this->mfgProduct .= "<p>$this->countryNow</p>";
    //    $this->mfgProduct .= $this->formatHelper->closeUp();
             return $this->mfgProduct;
    }
}