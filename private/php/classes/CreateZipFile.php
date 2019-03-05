<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-25
 * Time: 14:37
 */

class CreateZipFile
{
    private $zipList;

    public function __construct($list)
    {
        $this->zipList=$list;
        $this->createZip();
    }

    private function createZip()
    {
        $curr = getcwd();

        $zip = new ZipArchive();
        $zip_name = PROJECT_PATH . '/' .
            'public/archives/lesnormandeaudesilets' . time() . ".zip";

        if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) {
            // Opening zip file to load files
            $error .= "* Sorry ZIP creation failed at this time";
        }

        chdir(PROJECT_PATH . '/');
        foreach ($this->zipList as $key => $value) {
            $path = 'photos_Normandeau_Desilets/';
            $file = $value["filename"];
            $filename = $path . $file;
            $zip->addFile($filename);
        }

        $zip->close();

        if (file_exists($zip_name)) {
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header('Content-type: application/zip');
            header("Content-Disposition: attachment; filename=$zip_name");
            header("Content-Transfer-Encoding: binary");
            readfile($zip_name);
        }
    }
}