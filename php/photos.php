<?php
/**
 * Created by PhpStorm.
 * User: Michel Desilets
 * Date: 2018-12-21
 * Time: 11:33
 */
if (isset($_POST['function'])) {
    $function = $_POST['function'];
}else {
    $function = $_GET['function'];
}

require_once '../classes/database/cl_PhotosDB.php';
$db = new photosBD();

if ($function === 'zipAndDownload') {
    if (isset($_POST['pids'])) {
        $listPids = json_decode($_POST['pids']);
        $photos = $db->downloadPhotos($listPids);
        $listPhotos = json_decode($photos, true);

// Checking files are selected
        $curr=getcwd();

        $zip = new ZipArchive();
        $zip_name =
            'assets/archives/lesnormandeaudesilets' . time() . ".zip";
        $fname = $zip_name;

        if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) {
            // Opening zip file to load files
            $error .= "* Sorry ZIP creation failed at this time";
        }

        foreach ($listPhotos as $key => $value) {
            $path = $value["path"];
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

if ($function === 'removeZipFile'){
    $curr = getcwd();
    chdir('../');
    $dir = "assets/archives";
    $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
    $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ( $ri as $file ) {
        $file->isDir() ?  rmdir($file) : unlink($file);
    }

    $dir = "photosNormDesi";
    $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
    $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ( $ri as $file ) {
        $file->isDir() ?  rmdir($file) : unlink($file);
    }
    return true;
}
