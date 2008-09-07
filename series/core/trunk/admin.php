<?php

/* InstaDisc Series - A Four Island Project */

session_start();

include('includes/instadisc.php');

if (!isset($_GET['id']) || !isset($_SESSION['username']))
{
	if (!isset($_SESSION['username']))
	{
		include('admin/login.php');
	} else {
		include('admin/main.php');
	}
} else {
	include('admin/' . basename($_GET['id']) . '.php');
}

?>
