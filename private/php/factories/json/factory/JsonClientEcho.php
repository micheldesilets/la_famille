<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 09:44
 */

include_once PRIVATE_PHP_PATH .  '/factories/json/factory/JsonFactory.php';

class JsonClientEcho
{
    private $jsonFactory;

    public function __construct($prod,$param)
    {
        $this->jsonFactory = new JsonFactory();
        echo $this->jsonFactory->doFactory($prod,$param);
    }
}
