<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-20
 * Time: 13:20
 */

require('load.php');

$factory = new TextFactory(new AdapterFactory());
$text = $factory->make(TetxtEnum::JSON);

$json = ['author' => 'myself', 'gender' => 'male'];

echo $text->output($json);