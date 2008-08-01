<?php

/* InstaDisc Server - A Four Island Project */

include_once('db.php');

function instaDisc_checkVerification($username, $verification, $verificationID, $table, $nameField, $passField)
{
	$getitem = "SELECT * FROM " . $table . " WHERE " . $nameField . " = \"" . $username . "\"";
	$getitem2 = mysql_query($getitem);
	$getitem3 = mysql_fetch_array($getitem2);
	if ($getitem3[$nameField] == $username)
	{
		$test = $username . ':' . $getitem3[$passField] . ':' . $verificationID;

		return (md5($test) == $verification);
	}

	return false;
}

function instaDisc_sendItem($username, $id)
{
}

function instaDisc_sendUpdateNotice($softwareVersion)
{
	$username = getConfig('owner');
	$subscription = 'http://' . $_SERVER['HTTP_HOST'];
	$title = 'Update your software to ' . $software;
	$author = 'Hatkirby';
	$url = 'http://fourisland.com/projects/instadisc/wiki/CentralSoftwareUpdate';
	$semantics = array();

	instaDisc_addItem($username, $subscription, $title, $author, $url, $semantics);
}

function instaDisc_sendDatabase($cserver)
{
}

function instaDisc_addItem($username, $subscription, $title, $author, $url, $semantics)
{
	$getuser = "SELECT * FROM users WHERE username = \"" . $username . "\"";
	$getuser2 = mysql_query($getuser);
	$getuser3 = mysql_fetch_array($getuser2);
	if ($getuser3['username'] == $username)
	{
		$itemID = $getuser3['nextItemID'];
		$setuser = "UPDATE users SET nextItemID = nextItemID+1 WHERE username = \"" . $username . "\"";
		$setuser2 = mysql_query($setuser);

		$insitem = "INSERT INTO inbox (username, itemID, subscription, title, author, url, semantics) VALUES (\"" . $username . "\", " . $itemID . ", \"" . $subscription . "\", \"" . $title . "\", \"" . $author . "\", \"" . $url . "\", \"" . serialize($semantics) . "\")";
		$insitem2 = mysql_query($insitem);

		instaDisc_sendItem($username, $itemID);
	}
}

?>
