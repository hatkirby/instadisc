<?php

/* InstaDisc Series - A Four Project */

include('includes/instadisc.php');

function subscriptionInfo($id)
{
	if (!instaDisc_subscriptionExists($id))
	{
		return new xmlrpcresp(new xmlrpcval('false', 'string'));
	}

	$sub = instaDisc_getSubscription($id);
	return serialize(array(	'url' => $sub['url'],
				'category' => $sub['category']
			));
}

function getPasswordInfo($id)
{
	if (!instaDisc_subscriptionExists($id))
	{
		return new xmlrpcresp(new xmlrpcval('false', 'string'));
	}

	$sub = instaDisc_getSubscription($id);
	if ($sub['password'] == '')
	{
		return new xmlrpcresp(new xmlrpcval('false', 'string'));
	} else {
		$verID = rand(1,2147483647);

		return new xmlrpcresp(new xmlrpcval(md5($sub['password'] . ':' . $verID) . ':' . $verID, 'string'));
	}
}

function sendFromUpdate($username, $verification, $verificationID, $seriesURL, $seriesID, $subscriptionURL, $subscriptionTitle, $subscriptionCategory, $subscriptionPersonal, $title, $author, $url, $semantics, $encryptionID)
{
	if (instaDisc_checkVerification($username, $verification, $verificationID, 'users', 'username', 'password'))
	{
		$getsub = "SELECT * FROM subscriptions WHERE identity = \"" . mysql_real_escape_string($seriesID) . "\"";
		$getsub2 = mysql_query($getsub);
		$getsub3 = mysql_fetch_array($getsub2);
		if ($getsub3['identity'] == $seriesID)
		{
			if ($getsub3['username'] != $username)
			{
				return new xmlrpcresp(new xmlrpcval('1', 'int'));
			}

			$setsub = "UPDATE subscriptions SET title = \"" . mysql_real_escape_string($subscriptionTitle) . "\", url = \"" . mysql_real_escape_string($subscriptionURL) . "\", category = \"" . mysql_real_escape_string($subscriptionCategory) . "\", personal = \"" . mysql_real_escape_string($subscriptionPersonal) . "\"";
			$setsub2 = mysql_query($setsub);
		} else {
			$inssub = "INSERT INTO subscriptions (identity, title, url, category, personal, username) VALUES (\"" . mysql_real_escape_string($seriesID) . "\",\"" . mysql_real_escape_string($subscriptionTitle) . "\",\"" . mysql_real_escape_string($subscriptionURL) . "\",\"" . mysql_real_escape_string($subscriptionCategory) . "\",\"" . mysql_real_escape_string($subscriptionPersonal) . "\",\"" . mysql_real_escape_string($username) . "\")";
			$inssub2 = mysql_query($inssub);
		}

		$client = xmlrpc_client('http://central.fourisland.com/xmlrpc.php');
		$msg = new xmlrpcmsg("InstaDisc.sendFromSeries", array(	new xmlrpcval($seriesURL, 'string'),
									new xmlrpcval($seriesID, 'string'),
									new xmlrpcval($title, 'string'),
									new xmlrpcval($author, 'string'),
									new xmlrpcval($url, 'string'),
									new xmlrpcval($semantics, 'string'),
									new xmlrpcval($encryptionID, 'int')));
		$client->send($msg);

		return new xmlrpcresp(new xmlrpcval('0', 'int'));
	} else {
		return new xmlrpcresp(new xmlrpcval('2', 'int'));
	}

	return new xmlrpcresp(new xmlrpcval('1', 'int'));
}

$s = new xmlrpc_server(array(	"InstaDisc.subscriptionInfo" => array('function' => 'subscriptionInfo'),
				"InstaDisc.getPasswordInfo" => array('function' => 'getPasswordInfo'),
				"InstaDisc.sendFromUpdate" => array('function' => 'sendFromUpdate')
			), 0);
$s->functions_parameters_type = 'phpvals';
$s->service();

?>
