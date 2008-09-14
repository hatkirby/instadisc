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

if (!instaDisc_isAdmin($_SESSION['username']))
{
	header('Location: index.php');
	exit;
}

if (!isset($_GET['submit']))
{
	$template = new FITemplate('deleteuser');
	$template->add('SITENAME',instaDisc_getConfig('siteName'));
	$template->add('ID',$_GET['userid']);

	$sub = instaDisc_getUserByID($_GET['userid']);
	$template->add('USERNAME',$sub['username']);
	$template->display();
} else {
	if ($_POST['submit'] == 'Yes')
	{
		instaDisc_deleteUser($_POST['id']);
		
		$template = new FITemplate('deleteduser');
		$template->display();
	} else {
		header('Location: admin.php?id=main');
	}
}

?>
