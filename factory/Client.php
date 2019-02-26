<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 09:44
 */

include_once 'KyrgyzstanProduct.php';
include_once 'MoldovaProduct.php';

include_once 'CountryFactory.php';

class Client
{
    private $countryFactory;

    public function __construct()
    {
        $this->countryFactory = new CountryFactory();
        echo $this->countryFactory->doFactory(new KyrgyzstanProduct());
    }
}

$worker=new Client();