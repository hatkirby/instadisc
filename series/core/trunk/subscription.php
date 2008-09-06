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

echo('Subscription: ' . $sub['url'] . "\n");
echo('Title: ' . $sub['title'] . "\n");
echo('Category: ' . $sub['category'] . "\n");

if ($sub['password'] != '')
{
	echo("Password: On\n");
}

?>
