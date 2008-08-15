<?php

/* InstaDisc Server - A Four Island Project */

include('includes/xmlrpc/xmlrpc.inc');
include('includes/xmlrpc/xmlrpcs.inc');
include('includes/instadisc.php');

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
		$getitem = "SELECT * FROM inbox WHERE username = \"" . mysql_real_escape_string($username) . "\" AND itemID = " . $id;
		$getitem2 = mysql_query($getitem);
		$getitem3 = mysql_fetch_array($getitem2);
		if ($getitem3['itemID'] == $id)
		{
			$delitem = "DELETE FROM inbox WHERE username = \"" . mysql_real_escape_string($username) . "\" AND itemID = " . $id;
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
		$getitem = "SELECT * FROM inbox WHERE username = \"" . mysql_real_escape_string($username) . "\" AND itemID = " . $id;
		$getitem2 = mysql_query($getitem);
		$getitem3 = mysql_fetch_array($getitem2);
		if ($getitem3['itemID'] == $id)
		{
			instaDisc_sendItem($username, $id);

			return new xmlrpcresp(new xmlrpcval(0, "int"));
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function requestRetained($username, $verification, $verificationID)
{
	if (instaDisc_checkVerification($username, $verification, $verificationID, 'users', 'username', 'password'))
	{
		$getitems = "SELECT * FROM inbox WHERE username = \"" . mysql_real_escape_string($username) . "\"";
		$getitems2 = mysql_query($getitems);
		$i=0;
		while ($getitems3[$i] = mysql_fetch_array($getitems2))
		{
			if (!instaDisc_sendItem($username, $getitems3[$i]['itemID']))
			{
				return new xmlrpcresp(new xmlrpcval(1, "int"));
			}
			$i++;
		}

		return new xmlrpcresp(new xmlrpcval(0, "int"));
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function sendFromUpdate($username, $verification, $verificationID, $subscription, $title, $author, $url, $semantics, $encryptionID)
{
	if (instaDisc_checkVerification($username, $verification, $verificationID, 'users', 'username', 'password'))
	{
		$getusubs = "SELECT * FROM subscriptions WHERE username = \"" . mysql_real_escape_string($username) . "\" AND url = \"" . mysql_real_escape_string($subscription) . "\" AND owner = \"true\" AND category <> \"instadisc\"";
		$getusubs2 = mysql_query($getusubs);
		$getusubs3 = mysql_fetch_array($getusubs2);
		if ($getusubs3['username'] == $username)
		{
			$cserver = $_SERVER['SERVER_NAME'];
			$getuk = "SELECT * FROM centralServers WHERE url = \"" . mysql_real_escape_string($cserver) . "\"";
			$getuk2 = mysql_query($getuk);
			$getuk3 = mysql_fetch_array($getuk2);

			$getcs = "SELECT * FROM centralServers";
			$getcs2 = mysql_query($getcs);
			$i=0;
			while ($getcs3[$i] = mysql_fetch_array($getcs2))
			{
				$verID = rand(1,65536);

				$client = new xmlrpc_client($getcs3[$i]['xmlrpc']);
				$msg = new xmlrpcmsg("InstaDisc.sendFromCentral", array(	new xmlrpcval($cserver, 'string'),
												new xmlrpcval(md5($cserver . ":" . $getuk3['code'] . ":" . $verID), 'string'),
												new xmlrpcval($verID, 'int'),
												new xmlrpcval($subscription, 'string'),
												new xmlrpcval($title, 'string'),
												new xmlrpcval($author, 'string'),
												new xmlrpcval($url, 'string'),
												new xmlrpcval($semantics, 'string'),
												new xmlrpcval($encryptionID, 'int'),
												new xmlrpcval(instaDisc_getConfig('softwareVersion'), 'int'),
												new xmlrpcval(instaDisc_getConfig('databaseVersion'), 'int')));
				$client->send($msg);
				$i++;
			}

			return new xmlrpcresp(new xmlrpcval(0, "int"));
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function sendFromCentral($cserver, $verification, $verificationID, $subscription, $title, $author, $url, $semantics, $encryptionID, $softwareVersion, $databaseVersion)
{
	if (instaDisc_checkVerification($cserver, $verification, $verificationID, 'centralServers', 'url', 'code'))
	{
		if ($softwareVersion > instaDisc_getConfig('softwareVersion'))
		{
			instaDisc_sendUpdateNotice($softwareVersion);
		} else if ($softwareVersion < instaDisc_getConfig('softwareVersion'))
		{
			$cserver2 = $_SERVER['SERVER_NAME'];
			$getuk = "SELECT * FROM centralServers WHERE url = \"" . mysql_real_escape_string($cserver2) . "\"";
			$getuk2 = mysql_query($getuk);
			$getuk3 = mysql_fetch_array($getuk2);

			$verID = rand(1,65536);

			$client = new xmlrpc_client($cserver);
			$msg = new xmlrpcmsg("InstaDisc.sendUpdateNotice", array(	new xmlrpcval($cserver2, 'string'),
											new xmlrpcval(md5($cserver2 . ':' . $getuk3['code'] . ':' . $verID), 'string'),
											new xmlrpcval($verID, 'int'),
											new xmlrpcval(instaDisc_getConfig('softwareVersion'), 'int')));
			$client->send($msg);
		}

		if ($databaseVersion > instaDisc_getConfig('databaseVersion'))
		{
			$cserver2 = $_SERVER['SERVER_NAME'];
			$getuk = "SELECT * FROM centralServers WHERE url = \"" . mysql_real_escape_string($cserver2) . "\"";
			$getuk2 = mysql_query($getuk);
			$getuk3 = mysql_fetch_array($getuk2);

			$verID = rand(1,65536);

			$client = new xmlrpc_client($cserver);
			$msg = new xmlrpcmsg("InstaDisc.askForDatabase", array(	new xmlrpcval($cserver2, 'string'),
										new xmlrpcval(md5($cserver2 . ':' . $getuk3['code'] . ':' . $verID), 'string'),
										new xmlrpcval($verID, 'int'),
										new xmlrpcval(instaDisc_getConfig('databaseVersion'), 'int')));
			$client->send($msg);
		} else if ($databaseVersion < instaDisc_getConfig('databaseVersion'))
		{
			instaDisc_sendDatabase($cserver);
		}

		$getsed = "SELECT * FROM subscriptions WHERE url = \"" . mysql_real_escape_string($subscription) . "\" AND owner = \"false\" AND category <> \"instadisc\"";
		$getsed2 = mysql_query($getsed);
		$i=0;
		while ($getsed3[$i] = mysql_fetch_array($getsed2))
		{
			instaDisc_addItem($getsed3[$i]['username'], $subscription, $title, $author, $url, $semantics, $encryptionID);
			$i++;
		}

		return new xmlrpcresp(new xmlrpcval(0, "int"));
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function sendUpdateNotice($cserver, $verification, $verificationID, $softwareVersion)
{
	if (instaDisc_checkVerification($cserver, $verification, $verificationID, 'centralServers', 'url', 'code'))
	{
		if ($softwareVersion > instaDisc_getConfig('softwareVersion'))
		{
			instaDisc_sendUpdateNotice($softwareVersion);

			return new xmlrpcresp(new xmlrpcval(0, "int"));
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function askForDatabase($cserver, $verification, $verificationID, $databaseVersion)
{
	if (instaDisc_checkVerification($cserver, $verification, $verificationID, 'centralServers', 'url', 'code'))
	{
		if ($databaseVersion < instaDisc_getConfig('databaseVersion'))
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
		$getsub = "SELECT * FROM subscriptions WHERE url = \"" . mysql_real_escape_string($subscription) . "\" AND username = \"" . mysql_real_escape_string($username) . "\" AND owner = \"false\"";
		$getsub2 = mysql_query($getsub);
		$getsub3 = mysql_fetch_array($getsub2);
		if ($getsub3['url'] == $subscription)	
		{
			$delsub = "DELETE FROM subscriptions WHERE url = \"" . mysql_real_escape_string($subscription) . "\" AND username = \"" . mysql_real_escape_string($username) . "\" AND owner = \"false\"";
			$delsub2 = mysql_query($delsub);

			return new xmlrpcresp(new xmlrpcval(0, "int"));
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function addSubscription($username, $verification, $verificationID, $subscription, $category)
{
	if (instaDisc_checkVerification($username, $verification, $verificationID, 'users', 'username', 'password'))
	{
		$getsub = "SELECT * FROM subscriptions WHERE url = \"" . mysql_real_escape_string($subscription) . "\" AND username = \"" . mysql_real_escape_string($username) . "\" AND owner = \"false\"";
		$getsub2 = mysql_query($getsub);
		$getsub3 = mysql_fetch_array($getsub2);
		if ($getsub3['url'] == $subscription)	
		{
			$inssub = "INSERT INTO subscriptions (url, username, owner, category) VALUES (\"" . mysql_real_escape_string($subscription) . "\", \"" . mysql_real_escape_string($username) . "\", \"false\", \"" . mysql_real_escape_string($category) . "\")";
			$inssub2 = mysql_query($inssub);

			return new xmlrpcresp(new xmlrpcval(0, "int"));
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function sendDatabase($cserver, $verification, $verificationID, $db, $databaseVersion)
{
	if (instaDisc_checkVerification($cserver, $verification, $verificationID, 'centralServers', 'url', 'code'))
	{
		$db = unserialize($db);
		if (isset($db['central.fourisland.com']))
		{
			$getfi = "SELECT * FROM centralServers WHERE url = \"central.fourisland.com\"";
			$getfi2 = mysql_query($getfi);
			$getfi3 = mysql_fetch_array($getfi2);

			if ($db['central.fourisland.com']['code'] == $getfi3['code'])
			{
				$deldb = "DELETE FROM centralServers";
				$deldb2 = mysql_query($deldb);

				foreach($db as $name => $value)
				{
					$insdb = "INSERT INTO centralServers (url, code, xmlrpc) VALUES (\"" . mysql_real_escape_string($name) . "\", \"" . mysql_real_escape_string($value['code']) . "\", \"" . mysql_real_escape_string($value['xmlrpc']) . "\")";
					$insdb2 = mysql_query($insdb);
				}

				$setconfig = "UPDATE config SET value = " . $databaseVersion . " WHERE name = \"databaseVersion\"";
				$setconfig2 = mysql_query($setconfig);

				return new xmlrpcresp(new xmlrpcval("0", 'int'));
			}
		}
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
				"InstaDisc.addSubscription" => array("function" => "addSubscription"),
				"InstaDisc.sendDatabase" => array("function" => "sendDatabase")
			),0);
$s->functions_parameters_type = 'phpvals';
$s->service();

?>
