<?php

/* InstaDisc Server - A Four Island Project */

if (!file_exists('config.php'))
{
	header('Location: install.php');
	exit;
}

include('config.php');

mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname);

?>
