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

$template = new FITemplate('mansub');
$template->add('SITENAME', instaDisc_getConfig('siteName'));

if (instaDisc_isAdmin($_SESSION['username']))
{
	$subs = instaDisc_getAllSubscriptions();
} else {
	$subs = instaDisc_listSubscriptions($_SESSION['username']);
}
$i=0;
for ($i=0;isset($subs[$i]);$i++)
{
	$template->adds_block('SUBSCRIPTIONS', array(	'IDENTITY' => $subs['identity'],
							'ID' => $subs['id']));
}

$template->display();

?>
