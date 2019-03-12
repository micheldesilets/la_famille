<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-18
 * Time: 09:44
 */

namespace priv\php\factories\json\factory;

use priv\php\factories\json\factory as factory;

class JsonClientEcho
{
    private $jsonFactory;

    public function __construct($prod,$param)
    {
        $this->jsonFactory = new factory\JsonFactory();
        echo $this->jsonFactory->doFactory($prod,$param);
    }
}
