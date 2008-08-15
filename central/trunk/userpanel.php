<?php

/* InstaDisc Server - A Four Island Project */

include('includes/instadisc.php');
include('includes/template.php');

if (isset($_SESSION['username']))
{
	$template = new FITemplate('userpanel');
	$template->add('SITENAME', instaDisc_getConfig('siteName'));
	$template->add('USERNAME', $_SESSION['username']);
	$template->display();
} else {
	header('Location: index.php');
}

?>
