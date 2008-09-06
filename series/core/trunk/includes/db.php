<?php

/* InstaDisc Series - A Four Island Project */

if (!file_exists('includes/config.php'))
{
        header('Location: install.php');
        exit;
}

if (file_exists('install.php'))
{
        die('Excuse me, but you need to delete install.php before you can use this as leaving install.php there is a biiiig security hole.');
}

session_start();

include('includes/config.php');

mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname);

?>
