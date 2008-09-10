<?php

/* InstaDisc Series - A Four Island Project */

/**
 * require_once() is used to ensure
 * the ACP files are being called by
 * admin.php instead of their actual
 * locations admin/.
 * The _once() part ensures no problem
 * arises as includes/instadisc.php has
 * already been included from admin.php
 */
require_once('includes/instadisc.php');

if (!isset($_SESSION['username']))
{
	header('Location: index.php');
	exit;
}

unset($_SESSION['username']);

header('Location: index.php');

?>
