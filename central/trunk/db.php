<?php

/* InstaDisc Server - A Four Island Project */

if (!file_exists('config.php'))
{
	header('Location: install.php');
	exit;
}

if (file_exists('install.php'))
{
	die('Excuse me, but you need to delete install.php before you can use this as leaving install.php there is a biiiig security hole.');
}

include('config.php');

mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname);

?>