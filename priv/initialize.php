<?php
// Assign path shortcuts to PHP constants
// __FILE__ returns the current path to this file
// dirname() returns the path to the parent directory

define("PRIVATE_PATH", dirname(__FILE__));
define("PROJECT_PATH", dirname(PRIVATE_PATH));
define("SHARED_PATH", PRIVATE_PATH . '/shared');
define("INCLUDES_PATH", PRIVATE_PATH . '/php/authorisations/');
define("CLASSES_PATH", PRIVATE_PATH . '/php/classes');
define("PUBLIC_PATH", PROJECT_PATH . '/public');
define("PRIVATE_PHP_PATH", PROJECT_PATH . '/priv/php');
?>