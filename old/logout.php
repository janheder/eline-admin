<?php

define('__WEB_DIR__', '..');
require('../skripty/init.php');

if($_SESSION['admin'])
{

Admin::logOut();

}

header("location: /admin/");
exit();
?>
