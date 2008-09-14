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
}

if (!instaDisc_isAdmin($_SESSION['username']))
{
	$subs = instaDisc_listSubscriptions($_SESSION['username']);
	$i=0;
	$notfound=1;
	for ($i=0;isset($subs[$i]);$i++)
	{
		if (!isset($_GET['submit']))
		{
			if ($subs[$i]['identity'] == $_POST['id'])
			{
				$notfound=0;
			}
		} else {
			if ($subs[$i]['id'] == $_GET['subid'])
			{
				$notfound=0;
			}
		}
	}

	if ($notfound == 1)
	{
		header('Location: index.php');
		exit;
	}
}

if (!isset($_GET['submit']))
{
	$template = new FITemplate('deletesub');
	$template->add('SITENAME',instaDisc_getConfig('siteName'));
	$template->add('ID',$_GET['subid']);

	$sub = instaDisc_getSubscription($_GET['subid']);
	$template->add('IDENTITY',$sub['identity']);
	$template->display();
} else {
	if ($_POST['submit'] == 'Yes')
	{
		instaDisc_deleteSubscription($_POST['id']);
		
		$template = new FITemplate('deletedsub');
		$template->display();
	} else {
		header('Location: admin.php?id=main');
	}
}

?>
