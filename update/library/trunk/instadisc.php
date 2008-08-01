<?php

/* InstaDisc Update - A Four Island Project */

include('xmlrpc/xmlrpc.inc');

$idusUsername = ''; // Set this to the username you've registered
$idusPassword = ''; // Set this to the password you've registered
$idusCentralServer = ''; // Set this to the Central Server you've signed up with
$idusSubscriptionURI = ''; // Set this to your unique URI

function sendItem($title, $author, $url, $semantics)
{
	$verID = rand(1,65536);

	$client = new xmlrpc_client($idusCentralServer);
	$msg = new xmlrpcmsg("InstaDisc.sendFromUpdate", array(	new xmlrpcval($idusUsername, 'string'),
								new xmlrpcval(md5($idusUsername + ":" + md5($idusPassword) + ":" + $verID), 'string'),
								new xmlrpcval($verID, 'int'),
								new xmlrpcval($idusSubscriptionURI, 'string'),
								new xmlrpcval($title, 'string'),
								new xmlrpcval($author, 'string'),
								new xmlrpcval($url, 'string'),
								new xmlrpcval($semantics, 'array'));
	$client->send($msg);
}

?>
