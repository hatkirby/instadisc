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
