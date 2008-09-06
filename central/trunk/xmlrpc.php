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

function sendFromUpdate($username, $verification, $verificationID, $subscriptionSeriesURL, $subscriptionID, $title, $author, $url, $semantics, $encryptionID)
{
	$subscriptionURL = instaDisc_resolveSubscription($subscriptionSeriesURL, $subscriptionID);
	if ($subscriptionURL != 'false')
	{
		$getsed = "SELECT * FROM subscriptions WHERE url = \"" . mysql_real_escape_string($subscriptionSeriesURL) . "\" AND identity = \"" . mysql_real_escape_string($subscriptionID) . "\"";
		$getsed2 = mysql_query($getsed);
		$i=0;
		while ($getsed3[$i] = mysql_fetch_array($getsed2))
		{
			instaDisc_addItem($getsed3[$i]['username'], $subscriptionURL, $title, $author, $url, $semantics, $encryptionID);
			$i++;
		}

		return new xmlrpcresp(new xmlrpcval(0, "int"));
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
		if ($getsub3['url'] != $subscription)	
		{
			$inssub = "INSERT INTO subscriptions (url, username, owner, category) VALUES (\"" . mysql_real_escape_string($subscription) . "\", \"" . mysql_real_escape_string($username) . "\", \"false\", \"" . mysql_real_escape_string($category) . "\")";
			$inssub2 = mysql_query($inssub);

			return new xmlrpcresp(new xmlrpcval(0, "int"));
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

$s = new xmlrpc_server(	array(	"InstaDisc.checkRegistration" => array("function" => "checkRegistration"),
				"InstaDisc.deleteItem" => array("function" => "deleteItem"),
				"InstaDisc.resendItem" => array("function" => "resendItem"),
				"InstaDisc.requestRetained" => array("function" => "requestRetained"),
				"InstaDisc.sendFromUpdate" => array("function" => "sendFromUpdate"),
				"InstaDisc.deleteSubscription" => array("function" => "deleteSubscription"),
				"InstaDisc.addSubscription" => array("function" => "addSubscription")
			),0);
$s->functions_parameters_type = 'phpvals';
$s->service();

?>
