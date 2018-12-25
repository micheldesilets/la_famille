<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-10-30
 * Time: 18:46
 */
require_once '../../classes/database/cl_geneologyDB.php';

$db = new cl_geneologyDB();
$geneology = $db->getGeneologyList();
