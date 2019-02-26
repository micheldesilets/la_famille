<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-20
 * Time: 13:42
 */

class AdapterFactory
{
    public function make($config)
    {
        if ($config instanceof Config) {
            switch ($config->get('text.default')) {
                case 'json':
                    return new JSONAdapter;
                case 'xml':
                    return new XMLAdapter;
                case 'csv':
                    return CSVAdapter;
            }
        }
    }
}