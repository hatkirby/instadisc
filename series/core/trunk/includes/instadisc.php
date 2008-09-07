<?php

/* InstaDisc Series - A Four Island Project */

include('includes/db.php');
include('includes/template.php');
include('includes/xmlrpc/xmlrpc.inc');
include('includes/xmlrpc/xmlrpcs.inc');

function instaDisc_subscriptionExists($id)
{
	$getsub = "SELECT * FROM subscriptions WHERE identity = \"" . mysql_real_escape_string($id) . "\"";
	$getsub2 = mysql_query($getsub);
	$getsub3 = mysql_fetch_array($getsub2);
	if ($getsub3['identity'] != $id)
	{
		return 'false';
	} else {
		return 'true';
	}
}

function instaDisc_getSubscription($id)
{
	$getsub = "SELECT * FROM subscriptions WHERE identity = \"" . mysql_real_escape_string($id) . "\"";
	$getsub2 = mysql_query($getsub);
	$getsub3 = mysql_fetch_array($getsub2);

	return $getsub3;
}

function instaDisc_getAllSubscriptions()
{
	$getsubs = "SELECT * FROM subscriptions";
	$getsubs2 = mysql_query($getsubs);
	$i=0;
	while ($getsubs3[$i] = mysql_fetch_array($getsubs2))
	{
		$i++;
	}

	return $getsubs3;
}

function instaDisc_getConfig($name)
{
	$getconfig = "SELECT * FROM config WHERE name = \"" . mysql_real_escape_string($name) . "\"";
	$getconfig2 = mysql_query($getconfig);
	$getconfig3 = mysql_fetch_array($getconfig2);

	return $getconfig3['value'];
}

?>
