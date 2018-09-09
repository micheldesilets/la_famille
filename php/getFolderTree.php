<?php
/**
 * Created by Michel Desilets.
 * Date: 2018-09-08
 * Time: 06:27
 */

header('content-type: text/javascript');

require_once '../classes/database/cl_foldersDB.php';
$db = new foldersDB();
$folder = $db->getRepositories();

