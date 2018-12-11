<?php
/**
 * Created by Michel Desilets.
 * Date: 2018-09-08
 * Time: 06:27
 */

header('content-type: text/javascript');

$function = $_GET['function'];

require_once '../classes/database/cl_foldersDB.php';
$db = new foldersDB();

if ($function === 'getFolders') {
    $db->getFoldersTree();
}

if ($function === 'getShiftingFolders') {
$db->getShiftingFolders();
};

