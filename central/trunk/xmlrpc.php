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
			$getuser = "SELECT * FROM users WHERE username = \"" . mysql_real_escape_string($username) . "\"";
			$getuser2 = mysql_query($getuser);
			$getuser3 = mysql_fetch_array($getuser2);
			if ($getuser3['downloadItemMode'] == 'Push')
			{
				instaDisc_sendItem($username, $id);

				return new xmlrpcresp(new xmlrpcval(0, "int"));
			} else if ($getuser3['downloadItemMode'] == 'Pull')
			{
				return new xmlrpcresp(new xmlrpcval(instaDisc_formItem($username, $id), 'string'));
			}
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function requestRetained($username, $verification, $verificationID)
{
	if (instaDisc_checkVerification($username, $verification, $verificationID, 'users', 'username', 'password'))
	{
		$getuser = "SELECT * FROM users WHERE username = \"" . mysql_real_escape_string($username) . "\"";
		$getuser2 = mysql_query($getuser);
		$getuser3 = mysql_fetch_array($getuser2);
		if ($getuser3['downloadItemMode'] == 'Push')
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
		} else if ($getuser3['downloadItemMode'] == 'Pull')
		{
			$getitems = "SELECT * FROM inbox WHERE username = \"" . mysql_real_escape_string($username) . "\" LIMIT 0,2";
			$getitems2 = mysql_query($getitems);
			$getitems3 = mysql_fetch_array($getitems2);
			$getitems4 = mysql_fetch_array($getitems2);
			if ($getitems3['username'] == $username)
			{
				$item = instaDisc_formItem($username, $getitems3['id']);
				if ($getitems4['username'] == $username)
				{
					$item .= 'More: ' . $getitems4['id'] . "\n";
				}

				return new xmlrpcresp(new xmlrpcval($item, 'string'));
			}
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function sendItem($username, $verification, $verificationID, $id)
{
	if (instaDisc_checkVerification($username, $verification, $verificationID, 'users', 'username', 'password'))
	{
		$getuser = "SELECT * FROM users WHERE username = \"" . mysql_real_escape_string($username) . "\"";
		$getuser2 = mysql_query($getuser);
		$getuser3 = mysql_fetch_array($getuser2);
		if ($getuser3['downloadItemMode'] == 'Pull')
		{
			$getitems = "SELECT * FROM inbox WHERE username = \"" . mysql_real_escape_string($username) . "\" AND itemID > " . ($id-1) . " LIMIT 0,2";
			$getitems2 = mysql_query($getitems);
			$getitems3 = mysql_fetch_array($getitems2);
			$getitems4 = mysql_fetch_array($getitems2);
			if ($getitems3['username'] == $username)
			{
				$item = instaDisc_formItem($username, $getitems3['id']);
				if ($getitems4['username'] == $username)
				{
					$item .= 'More: ' . $getitems4['id'] . "\n";
				}

				return new xmlrpcresp(new xmlrpcval($item, 'string'));
			}
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}


function sendFromUpdate($subscriptionURL, $title, $author, $url, $semantics, $encryptionID)
{
	$getsed = "SELECT * FROM subscriptions WHERE url = \"" . mysql_real_escape_string($subscriptionURL) . "\"";
	$getsed2 = mysql_query($getsed);
	$i=0;
	while ($getsed3[$i] = mysql_fetch_array($getsed2))
	{
		instaDisc_addItem($getsed3[$i]['username'], $subscriptionURL, $title, $author, $url, $semantics, $encryptionID);
		$i++;
	}

	return new xmlrpcresp(new xmlrpcval(0, "int"));
}

function deleteSubscription($username, $verification, $verificationID, $subscription)
{
	if (instaDisc_checkVerification($username, $verification, $verificationID, 'users', 'username', 'password'))
	{
		$getsub = "SELECT * FROM subscriptions WHERE url = \"" . mysql_real_escape_string($subscription) . "\" AND username = \"" . mysql_real_escape_string($username) . "\"";
		$getsub2 = mysql_query($getsub);
		$getsub3 = mysql_fetch_array($getsub2);
		if ($getsub3['url'] == $subscription)	
		{
			$delsub = "DELETE FROM subscriptions WHERE url = \"" . mysql_real_escape_string($subscription) . "\" AND username = \"" . mysql_real_escape_string($username) . "\"";
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
		$getsub = "SELECT * FROM subscriptions WHERE url = \"" . mysql_real_escape_string($subscription) . "\" AND username = \"" . mysql_real_escape_string($username) . "\"";
		$getsub2 = mysql_query($getsub) or die($getsub);
		$getsub3 = mysql_fetch_array($getsub2);
		if ($getsub3['url'] != $subscription)	
		{
			$inssub = "INSERT INTO subscriptions (url, username, category) VALUES (\"" . mysql_real_escape_string($subscription) . "\", \"" . mysql_real_escape_string($username) . "\", \"" . mysql_real_escape_string($category) . "\")";
			$inssub2 = mysql_query($inssub);

			return new xmlrpcresp(new xmlrpcval(0, "int"));
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function downloadItemModeTest()
{
	$fp = @fsockopen($_SERVER['REMOTE_ADDR'], 61200, $errno, $errstr);
        if ($fp)
        {
                fwrite($fp, "InstaDisc Download Item Mode Test\r\n\r\n\r\n");
                fclose($fp);
                return new xmlrpcresp(new xmlrpcval('0', 'int'));
        } else {
                return new xmlrpcresp(new xmlrpcval('1', 'int'));
        }
}

function setDownloadItemMode($username, $verification, $verificationID, $mode)
{
	if (instaDisc_checkVerification($username, $verification, $verificationID, 'users', 'username', 'password'))
	{
		$setuser = "UPDATE users SET downloadItemMode = \"" . mysql_real_escape_string($mode) . "\" WHERE username = \"" . mysql_real_escape_string($username) . "\"";
		$setuser2 = mysql_query($setuser);

		return new xmlrpcresp(new xmlrpcval('0', 'int'));
	}

	return new xmlrpcresp(new xmlrpcval('1', 'int'));
}

function initalizePort($username, $verification, $verificationID)
{
	if (instaDisc_checkVerification($username, $verification, $verificationID, 'users', 'username', 'password'))
	{
		return new xmlrpcresp(new xmlrpcval(instaDisc_initalizePort($username), 'int'));
	}

	return new xmlrpcresp(new xmlrpcval('0', 'int'));
}

function deinitalizePort($username, $verification, $verificationID)
{
	if (instaDisc_checkVerification($username, $verification, $verificationID, 'users', 'username', 'password'))
	{
		$setuser = "UPDATE users SET port = 0 WHERE username = \"" . mysql_real_escape_string($username) . "\"";
		$setuser2 = mysql_query($setuser);

		return new xmlrpcresp(new xmlrpcval('0', 'int'));
	}

	return new xmlrpcresp(new xmlrpcval('1', 'int'));
}

function countSubscribers($url)
{
	$cntsub = "SELECT * FROM subscriptions WHERE url = \"" . mysql_real_escape_string($url) . "\"";
	$cntsub2 = mysql_query($cntsub);
	$cntsub3 = mysql_fetch_array($cntsub2);

	return new xmlrpcresp(new xmlrpcval($cntsub3[0], 'int'));
}

$s = new xmlrpc_server(	array(	"InstaDisc.checkRegistration" => array("function" => "checkRegistration"),
				"InstaDisc.deleteItem" => array("function" => "deleteItem"),
				"InstaDisc.resendItem" => array("function" => "resendItem"),
				"InstaDisc.requestRetained" => array("function" => "requestRetained"),
				"InstaDisc.sendItem" => array("function" => "sendItem"),
				"InstaDisc.sendFromUpdate" => array("function" => "sendFromUpdate"),
				"InstaDisc.deleteSubscription" => array("function" => "deleteSubscription"),
				"InstaDisc.addSubscription" => array("function" => "addSubscription"),
				"InstaDisc.downloadItemModeTest" => array("function" => "downloadItemModeTest"),
				"InstaDisc.setDownloadItemMode" => array("function" => "setDownloadItemMode"),
				"InstaDisc.initalizePort" => array("function" => "initalizePort"),
				"InstaDisc.deinitalizePort" => array("function" => "deinitalizePort"),
				"InstaDisc.countSubscribers" => array("function" => "countSubscribers")
			),0);
$s->functions_parameters_type = 'phpvals';
$s->service();

?>
