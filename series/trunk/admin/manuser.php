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

$template = new FITemplate('manuser');
$template->add('SITENAME', instaDisc_getConfig('siteName'));

if (instaDisc_isAdmin($_SESSION['username']))
{
	$users = instaDisc_getAllUsers();
} else {
	header('Location: index.php');
	exit;
}
$i=0; $j=0;
for ($i=0;isset($users[$i]);$i++)
{
	$j++;
}
$j--;
for ($i=0;$i<$j;$i++)
{
	$template->adds_block('USERS', array(	'USERNAME' => $users[$i]['username'],
						'ID' => $users[$i]['id']));
}

$template->display();

?>
