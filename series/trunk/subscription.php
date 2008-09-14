<?php

/* InstaDisc Series - A Four Island Project */

include('includes/instadisc.php');

if (!isset($_GET['id']))
{
	header('Location: ./index.php');
	exit;
}

if (!instaDisc_subscriptionExists($_GET['id']))
{
	header('Location: ./index.php');
	exit;
}

$sub = instaDisc_getSubscription($_GET['id']);

$template = new FITemplate('subscription');
$template->add('SUBSCRIPTION', $sub['url']);
$template->add('TITLE', $sub['title']);
$template->add('CATEGORY', $sub['category']);

if ($sub['password'] != '')
{
	$template->add('PASSWORD', "Password: On\n");
}

$template->add('SERIESURL', 'http://' . $_SERVER['SERVER_NAME'] . str_replace(basename($_SERVER['PHP_SELF']), 'xmlrpc.php', $_SERVER['PHP_SELF']));
$template->add('SUBID', $_GET['id']);

$template->display();

?>
