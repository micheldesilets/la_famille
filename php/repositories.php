<?php
/**
 * Created by Michel Desilets.
 * Date: 2018-09-08
 * Time: 06:27
 */

header('content-type: text/javascript');

$function = $_GET['function'];

if ($function === 'getRepositories') {
    require_once '../classes/database/cl_repositoriesDB.php';
    $db = new repository();
    $folder = $db->getRepositories();
}

/*if ($function === getRollingRepositories()){

};*/

