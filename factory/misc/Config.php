<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-20
 * Time: 13:27
 */

class Config
{
    protected $data = [
        'text' => [
            'default' => 'json'
        ],
        'gui' => [
            'default' => 'list'
        ]
    ];

    public function get($keys)
    {
        $data = $this->data;
        $keys = explode(',', $keys);

        foreach ($keys as $key) {
            if (array_key_exists($key, $data)) {
                $data = $data[$key];
                continue;
            }
        }
        return $data;
    }
}