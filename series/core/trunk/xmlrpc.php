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

$s = new xmlrpc_server(array(	"InstaDisc.subscriptionInfo" => array('function' => 'subscriptionInfo')
			), 0);
$s->functions_parameters_type = 'phpvals';
$s->service();

?>
