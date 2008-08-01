<?php

/* InstaDisc Server - A Four Island Project */

include_once('db.php');

function instaDisc_checkVerification($username, $verification, $verificationID, $table, $nameField, $passField)
{
	$getitem = "SELECT * FROM " . $table . " WHERE " . $nameField . " = \"" . mysql_escape_string($username) . "\"";
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
	$getitem = "SELECT * FROM inbox WHERE username = \"" . mysql_escape_string($username) . "\" AND itemID = " . $id;
	$getitem2 = mysql_query($getitem);
	$getitem3 = mysql_fetch_array($getitem2);
	if ($getitem3['username'] == $username)
	{
		$getuser = "SELECT * FROM users WHERE username = \"" . mysql_escape_string($username) . "\"";
		$getuser2 = mysql_query($getuser);
		$getuser3 = mysql_fetch_array($getuser2);

		$fp = fsockopen($getuser3['ip'], 4444, $errno, $errstr);
		if ($fp)
		{
			$verID = rand(1,65536);

			$out = 'ID: ' . $id . '\r\n';
			$out .= 'Verification: ' . md5($username . ':' . $getuser3['password'] . ':' . $verID) . '\r\n';
			$out .= 'Verification-ID: ' . $verID . '\r\n';
			$out .= 'Subscription: ' . $getitem3['subscription'] . '\r\n';
			$out .= 'Title: ' . $getitem3['title'] . '\r\n';
			$out .= 'Author: ' . $getitem3['author'] . '\r\n';
			$out .= 'URL: ' . $getitem3['url'] . '\r\n';
			$out .= '\r\n\r\n';

			fwrite($fp, $out);
			fclose($fp);
		}
	}
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
	$getdb = "SELECT * FROM centralServers";
	$getdb2 = mysql_query($getdb);
	$i=0;
	while ($getdb3[$i] = mysql_fetch_array($getdb2))
	{
		$db[$getdb3[$i]['url']] = $getdb3[$i]['key'];
		$i++;
	}

	$cserver2 = $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	$getuk = "SELECT * FROM centralServers WHERE url = \"" . mysql_escape_string($cserver2) . "\"";
	$getuk2 = mysql_query($getuk);
	$getuk3 = mysql_fetch_array($getuk2);

	$verID = rand(1,65536);

	$client = new xmlrpc_client($cserver);
	$msg = new xmlrpcmsg("InstaDisc.sendDatabase", array(	new xmlrpcval($cserver2, 'string'),
								new xmlrpcval(md5($cserver2 + ":" + $getuk3['key'] + ":" + $verID), 'string'),
								new xmlrpcval($verID, 'int'),
								new xmlrpcval($db, 'array')));
	$client->send($msg);
}

function instaDisc_addItem($username, $subscription, $title, $author, $url, $semantics)
{
	$getuser = "SELECT * FROM users WHERE username = \"" . mysql_escape_string($username) . "\"";
	$getuser2 = mysql_query($getuser);
	$getuser3 = mysql_fetch_array($getuser2);
	if ($getuser3['username'] == $username)
	{
		$itemID = $getuser3['nextItemID'];
		$setuser = "UPDATE users SET nextItemID = nextItemID+1 WHERE username = \"" . mysql_escape_string($username) . "\"";
		$setuser2 = mysql_query($setuser);

		$insitem = "INSERT INTO inbox (username, itemID, subscription, title, author, url, semantics) VALUES (\"" . mysql_escape_string($username) . "\", " . $itemID . ", \"" . mysql_escape_string($subscription) . "\", \"" . mysql_escape_string($title) . "\", \"" . mysql_escape_string($author) . "\", \"" . mysql_escape_string($url) . "\", \"" . mysql_escape_string(serialize($semantics)) . "\")";
		$insitem2 = mysql_query($insitem);

		instaDisc_sendItem($username, $itemID);
	}
}

?>
