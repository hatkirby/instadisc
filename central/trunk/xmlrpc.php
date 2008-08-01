<?php

/* InstaDisc Server - A Four Island Project */

include('xmlrpc/xmlrpc.inc');
include('xmlrpc/xmlrpcs.inc');
include('db.php');
include('instadisc.php');

function checkRegistration($username, $verification, $verificationID)
{
	if (instaDisc_checkVerification($username, $verification, $verificationID, 'users', 'username', 'password'))
	{
		return new xmlrpcresp(new xmlrpcval(0, "int"));
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function deleteItem($username, $verification, $verificationID, $id)
{
	if (instaDisc_checkVerification($username, $verification, $verificationID, 'users', 'username', 'password'))
	{
		$getitem = "SELECT * FROM inbox WHERE username = \"" . $username . "\" AND itemID = " . $id;
		$getitem2 = mysql_query($getitem);
		$getitem3 = mysql_fetch_array($getitem2);
		if ($getitem3['id'] == $id)
		{
			$delitem = "DELETE inbox WHERE username = \"" . $username . "\" AND itemID = " . $id;
			$delitem2 = mysql_query($delitem);

			return new xmlrpcresp(new xmlrpcval(0, "int"));
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function resendItem($username, $verification, $verificationID, $id)
{
	if (instaDisc_checkVerification($username, $verification, $verificationID, 'users', 'username', 'password'))
	{
		$getitem = "SELECT * FROM inbox WHERE username = \"" . $username . "\" AND itemID = " . $id;
		$getitem2 = mysql_query($getitem);
		$getitem3 = mysql_fetch_array($getitem2);
		if ($getitem3['id'] == $id)
		{
			instaDisc_sendItem($username, $id);

			return new xmlrpcresp(new xmlrpcval(0, "int"));
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function sendFromUpdate($username, $verification, $verificationID, $subscription, $title, $author, $url, $semantics)
{
	if (instaDisc_checkVerification($username, $verification, $verificationID, 'users', 'username', 'password'))
	{
		$getusubs = "SELECT * FROM subscriptions WHERE username = \"" . $username . "\" AND uri = \"" . $subscription . "\" AND owner = \"true\"";
		$getusubs2 = mysql_query($getusubs);
		$getusubs3 = mysql_fetch_array($getusubs2);
		if ($getusubs['username'] == $username)
		{
			$cserver = $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
			$getuk = "SELECT * FROM centralServers WHERE url = \"" . $cserver . "\"";
			$getuk2 = mysql_query($getuk);
			$getuk3 = mysql_fetch_array($getuk2);

			$getcs = "SELECT * FROM centralServers";
			$getcs2 = mysql_query($getcs);
			$i=0;
			while ($getcs3[$i] = mysql_fetch_array($getcs2))
			{
				$verID = rand(1,65536);

				$client = new xmlrpc_client($getcs3[$i]['url']);
				$msg = new xmlrpcmsg("InstaDisc.sendFromCentral", array(	new xmlrpcval($cserver, 'string'),
												new xmlrpcval(md5($cserver + ":" + $getuk3['key'] + ":" + $verID), 'string'),
												new xmlrpcval($verID, 'int'),
												new xmlrpcval($subscription, 'string'),
												new xmlrpcval($title, 'string'),
												new xmlrpcval($author, 'string'),
												new xmlrpcval($url, 'string'),
												new xmlrpcval($semantics, 'array'),
												new xmlrpcval(getConfig('softwareVersion'), 'int'),
												new xmlrpcval(getConfig('databaseVersion'), 'int')));
				$client->send($msg);
				$i++;
			}

			return new xmlrpcresp(new xmlrpcval(0, "int"));
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function sendFromCentral($cserver, $verification, $verificationID, $subscription, $title, $author, $url, $semantics, $softwareVersion, $databaseVersion)
{
	if (instaDisc_checkVerification($cserver, $verification, $verificationID, 'centralServers', 'url', 'key'))
	{
		if ($softwareVersion > getConfig('softwareVersion'))
		{
			instaDisc_sendUpdateNotice($softwareVersion);
		} else if ($softwareVersion < getConfig('softwareVersion'))
		{
			$cserver2 = $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
			$getuk = "SELECT * FROM centralServers WHERE url = \"" . $cserver2 . "\"";
			$getuk2 = mysql_query($getuk);
			$getuk3 = mysql_fetch_array($getuk2);

			$verID = rand(1,65536);

			$client = new xmlrpc_client($cserver);
			$msg = new xmlrpcmsg("InstaDisc.sendUpdateNotice", array(	new xmlrpcval($cserver2, 'string'),
											new xmlrpcval(md5($cserver2 . ':' . $getuk3['key'] . ':' . $verID), 'string'),
											new xmlrpcval($verID, 'int'),
											new xmlrpcval(getConfig('softwareVersion'), 'int')));
			$client->send($msg);
		}

		if ($databaseVersion > getConfig('databaseVersion'))
		{
			$cserver2 = $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
			$getuk = "SELECT * FROM centralServers WHERE url = \"" . $cserver2 . "\"";
			$getuk2 = mysql_query($getuk);
			$getuk3 = mysql_fetch_array($getuk2);

			$verID = rand(1,65536);

			$client = new xmlrpc_client($cserver);
			$msg = new xmlrpcmsg("InstaDisc.askForDatabase", array(	new xmlrpcval($cserver2, 'string'),
										new xmlrpcval(md5($cserver2 . ':' . $getuk3['key'] . ':' . $verID), 'string'),
										new xmlrpcval($verID, 'int'),
										new xmlrpcval(getConfig('databaseVersion'), 'int')));
			$client->send($msg);
		} else if ($databaseVersion < getConfig('databaseVersion'))
		{
			instaDisc_sendDatabase($cserver);
		}

		$getsed = "SELECT * FROM subscriptions WHERE uri = \"" . $subscription . "\"";
		$getsed2 = mysql_query($getsed);
		$i=0;
		while ($getsed3[$i] = mysql_fetch_array($getsed2))
		{
			instaDisc_addItem($getsed3['username'], $subscription, $title, $author, $url, $semantics);
			$i++;
		}

		return new xmlrpcresp(new xmlrpcval(0, "int"));
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function sendUpdateNotice($cserver, $verification, $verificationID, $softwareVersion)
{
	if (instaDisc_checkVerification($cserver, $verification, $verificationID, 'centralServers', 'url', 'key'))
	{
		if ($softwareVersion > getConfig('softwareVersion'))
		{
			instaDisc_sendUpdateNotice($softwareVersion);

			return new xmlrpcresp(new xmlrpcval(0, "int"));
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function askForDatabase($cserver, $verification, $verificationID, $databaseVersion)
{
	if (instaDisc_checkVerification($cserver, $verification, $verificationID, 'centralServers', 'url', 'key'))
	{
		if ($databaseVersion < getConfig('databaseVersion'))
		{
			instaDisc_sendDatabase($cserver);

			return new xmlrpcresp(new xmlrpcval(0, "int"));
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function deleteSubscription($username, $verification, $verificationID, $subscription)
{
	if (instaDisc_checkVerification($username, $verification, $verificationID, 'users', 'username', 'password'))
	{
		$getsub = "SELECT * FROM subscriptions WHERE url = \"" . $subscription . "\" AND username = \"" . $username . "\" AND owner = \"false\"";
		$getsub2 = mysql_query($getsub);
		$getsub3 = mysql_fetch_array($getsub2);
		if ($getsub3['url'] == $subscription)	
		{
			$delsub = "DELETE subscriptions WHERE url = \"" . $subscription . "\" AND username = \"" . $username . "\" AND owner = \"false\"";
			$delsub2 = mysql_query($delsub);

			return new xmlrpcresp(new xmlrpcval(0, "int"));
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function addSubscription($username, $verification, $verificationID, $subscription)
{
	if (instaDisc_checkVerification($username, $verification, $verificationID, 'users', 'username', 'password'))
	{
		$inssub = "INSERT INTO subscriptions (url, username, owner) VALUES (\"" . $subscription . "\", \"" . $username . "\", \"false\")";
		$inssub2 = mysql_query($inssub);

		return new xmlrpcresp(new xmlrpcval(0, "int"));
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

$s = new xmlrpc_server(	array(	"InstaDisc.checkRegistration" => array("function" => "checkRegistration"),
				"InstaDisc.deleteItem" => array("function" => "deleteItem"),
				"InstaDisc.resendItem" => array("function" => "resendItem"),
				"InstaDisc.requestRetained" => array("function" => "requestRetained"),
				"InstaDisc.sendFromUpdate" => array("function" => "sendFromUpdate"),
				"InstaDisc.sendFromCentral" => array("function" => "sendFromCentral"),
				"InstaDisc.sendUpdateNotice" => array("function" => "sendUpdateNotice"),
				"InstaDisc.askForDatabase" => array("function" => "askForDatabase"),
				"InstaDisc.deleteSubscription" => array("function" => "deleteSubscription"),
				"InstaDisc.addSubscription" => array("function" => "addSubscription")
			),0);
$s->functions_parameters_type = 'phpvals';
$s->service();

?>
