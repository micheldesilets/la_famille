<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 09:44
 */

include_once PRIVATE_PHP_PATH . '/factories/json/factory/JsonFactory.php';

class JsonClientReturn
{
    private $jsonFactory;
    private $set;

    public function __construct($prod, $param)
    {
        $this->jsonFactory = new JsonFactory();
        $this->setJsonFactory($this->jsonFactory->doFactory($prod, $param));
    }

    private function setJsonFactory($jsonFactory)
    {
        $this->jsonFactory = $jsonFactory;
    }

    public function getJsonFactory()
    {
        return $this->jsonFactory;
    }
}
