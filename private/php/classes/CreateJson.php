<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-15
 * Time: 10:37
 */

class CreateJson
{
    private $arrayData;
    private $json;

    public function __construct($arrayData)
    {
        $this->arrayData = $arrayData;
    }

    public function createJsonMethod()
    {
        $this->json = json_encode($this->arrayData, JSON_PRETTY_PRINT |
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($this->json === false) {
            $this->json = json_encode(array("jsonError", json_last_error_msg
            ()));
            if ($this->json === false) {
                $this->json = '{"jsonError": "unknown"}';
            }
            http_response_code(500);
        }
      return $this->json;
    }
}