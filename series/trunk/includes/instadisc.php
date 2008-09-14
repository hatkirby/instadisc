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

function instaDisc_verifyUser($username, $password)
{
	$getusers = "SELECT * FROM users WHERE username = \"" . mysql_real_escape_string($username) . "\" AND password = \"" . mysql_real_escape_string(md5($password)) . "\"";
	$getusers2 = mysql_query($getusers);
	$getusers3 = mysql_fetch_array($getusers2);

	return ($getusers3['username'] == $username);
}

function instaDisc_changePassword($username, $password)
{
	$setconfig = "UPDATE users SET password = \"" . mysql_real_escape_string(md5($password)) . "\" WHERE username = \"" . mysql_real_escape_string($username) . "\"";
	$setconfig2 = mysql_query($setconfig);
	$setconfig3 = mysql_fetch_array($setconfig2);
}

function instaDisc_initSubscription($username, $subscriptionID, $subscriptionURL, $subscriptionTitle, $subscriptionCategory, $subscriptionPassword)
{
	$getuser = "SELECT * FROM users WHERE username = \"" . $username . "\"";
	$getuser2 = mysql_query($getuser);
	$getuser3 = mysql_fetch_array($getuser2);
	if ($getuser3['username'] == $username)
	{
		$getsub = "SELECT * FROM subscriptions WHERE identity = \"" . mysql_real_escape_string($subscriptionID) . "\"";
		$getsub2 = mysql_query($getsub);
		$getsub3 = mysql_fetch_array($getsub2);
		if ($getsub3['identity'] == $subscriptionID)
		{
			if (!instaDisc_isAdmin($username) && ($getsub3['username'] != $username))
			{
				return false;
			}

			$setsub = "UPDATE subscriptions SET title = \"" . mysql_real_escape_string($subscriptionTitle) . "\", url = \"" . mysql_real_escape_string($subscriptionURL) . "\", category = \"" . mysql_real_escape_string($subscriptionCategory) . "\", password = \"" . mysql_real_escape_string($subscriptionPassword) . "\" WHERE identity = \"" . mysql_real_escape_string($subscriptionID) . "\"";
			$setsub2 = mysql_query($setsub);
		} else {
			$inssub = "INSERT INTO subscriptions (identity, title, url, category, username, password) VALUES (\"" . mysql_real_escape_string($subscriptionID) . "\",\"" . mysql_real_escape_string($subscriptionTitle) . "\",\"" . mysql_real_escape_string($subscriptionURL) . "\",\"" . mysql_real_escape_string($subscriptionCategory) . "\",\"" . mysql_real_escape_string($username) . "\",\"" . mysql_real_escape_string($subscriptionPassword) . "\")";
			$inssub2 = mysql_query($inssub);
		}

		return true;
	} else {
		return false;
	}
}

function instaDisc_listSubscriptions($username)
{
	$getsubs = "SELECT * FROM subscriptions WHERE username = \"" . mysql_real_escape_string($username) . "\"";
	$getsubs2 = mysql_query($getsubs);
	$i=0;
	while ($getsubs3[$i] = mysql_fetch_array($getsubs2))
	{
		$i++;
	}

	return $getsubs3;
}

function instaDisc_deleteSubscription($id)
{
	$delsub = "DELETE FROM subscriptions WHERE id = " . $id;
	$delsub2 = mysql_query($delsub);
}

function instaDisc_isAdmin($username)
{
	return ($username == instaDisc_getConfig('adminUser'));
}

function instaDisc_getSubscriptionByID($id)
{
	$getsub = "SELECT * FROM subscriptions WHERE id = " . $id;
	$getsub2 = mysql_query($getsub);
	$getsub3 = mysql_fetch_array($getsub2);

	return $getsub3;
}

function instaDisc_addUser($username, $password)
{
	$insuser = "INSERT INTO users (username,password) VALUES (\"" . mysql_real_escape_string($username) . "\",\"" . mysql_real_escape_string(md5($password)) . "\")";
	$insuser2 = mysql_query($insuser);
}

function instaDisc_deleteUser($id)
{
	$deluser = "DELETE FROM users WHERE id = " . $id;
	$deluser2 = mysql_query($deluser);
}

function instaDisc_getAllUsers()
{
	$getusers = "SELECT * FROM users";
	$getusers2 = mysql_query($getusers);
	$i=0;
	while ($getusers3[$i] = mysql_fetch_array($getusers2))
	{
		$i++;
	}

	return $getusers3;
}

function instaDisc_getUserByID($id)
{
	$getuser = "SELECT * FROM users WHERE id = " . $id;
	$getuser2 = mysql_query($getuser);
	$getuser3 = mysql_fetch_array($getuser2);

	return $getuser3;
}

?>
