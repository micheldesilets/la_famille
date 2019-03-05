<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-01-31
 * Time: 16:51
 */

class CreatePath
{
    private $path;
    private $level0Id;
    private $level0Name;
    private $level1Id;
    private $level1Name;
    private $level2Id;
    private $level2Name;
    private $level3Name;

    public function __construct($path, $data)
    {
        $this->path = $path;
        $this->level0Id = $data[0];
        $this->level0Name = $data[1];
        $this->level1Id = $data[0];
        $this->level1Name = $data[1];
        $this->level2Id = $data[0];
        $this->level2Name = $data[1];
        $this->level3Name = $data[1];
    }


}