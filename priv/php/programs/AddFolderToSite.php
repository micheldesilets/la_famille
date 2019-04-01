<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-13
 * Time: 10:54
 */

namespace priv\php\programs;

class AddFolderToSite
{
    private $param;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function addFolder()
    {
        try {
            $path = PUBLIC_PATH . '/img/family';

            $level0Name = $this->param[1];
            $level1Name = $this->param[3];
            $level2Name = $this->param[5];
            $level3Name = $this->param[6];

            $path = $path . '/' . $level0Name;
            if (!file_exists($path)) {
                mkdir($path);
            };

            $path = $path . '/' . $level1Name;
            if (!file_exists($path)) {
                mkdir($path);
                chdir($path);
                mkdir('full');
                mkdir('preview');
            };

            if ($level2Name === "") {
                return;
            } else {
                $path = $path . '/' . $level2Name;
                if (!file_exists($path)) {
                    mkdir($path);
                    chdir($path);
                    mkdir('full');
                    mkdir('preview');
                }
                if ($level3Name === "") {
                    return;
                } else {
                    $path = $path . '/' . $level3Name;
                    if (!file_exists($path)) {
                        mkdir($path);
                        chdir($path);
                        mkdir('full');
                        mkdir('preview');
                    } else {
                        echo 'Le répertoire existe dans la base de données.';
                    }
                }
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            exit;
        }
    }
}