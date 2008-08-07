<?php

/* InstaDisc Update - A Four Island Project */

include('xmlrpc/xmlrpc.inc');

$idusUsername = ''; // Set this to the username you've registered
$idusPassword = ''; // Set this to the password you've registered
$idusCentralServer = ''; // Set this to the Central Server you've signed up with
$idusSubscriptionURI = ''; // Set this to your unique URI
$idusSubscriptionTitle = ''; // Set this to your Subscription's title
$idusSubscriptionCategory = ''; // Set this to the category your subscription uses
$idusEncryptionPassword = ''; // If creating a password-protected subscription, enter your password here

function instaDisc_sendItem($title, $author, $url, $semantics)
{
	global $idusUsername, $idusPassword, $idusCentralServer, $idusSubscriptionURI;
	$verID = rand(1,65536);

	$client = new xmlrpc_client($idusCentralServer);
	$msg = new xmlrpcmsg("InstaDisc.sendFromUpdate", array(	new xmlrpcval($idusUsername, 'string'),
								new xmlrpcval(md5($idusUsername . ":" . md5($idusPassword) . ":" . $verID), 'string'),
								new xmlrpcval($verID, 'int'),
								new xmlrpcval($idusSubscriptionURI, 'string'),
								new xmlrpcval($title, 'string'),
								new xmlrpcval($author, 'string'),
								new xmlrpcval($url, 'string'),
								new xmlrpcval(serialize($semantics), 'string')));
	$client->send($msg);
}

function instaDisc_sendEncrypted($title, $author, $url, $semantics)
{
	$cipher = "rijndael-128";
	$mode = "cbc";
	$iv = "fedcba9876543210";

	$td = mcrypt_module_open($cipher, "", $mode, $iv);
	mcrypt_generic_init($td, $idusEncryptionPassword, $iv);
	$title = bin2hex(mcrypt_generic($td, $title));
	$author = bin2hex(mcrypt_generic($td, $author));
	$url = bin2hex(mcrypt_generic($td, $url));
	foreach ($semantics as $name => $value)
	{
		$semantics[$name] = bin2hex(mcrypt_generic($td, $value));
	}

	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);

	instaDisc_sendItem($title, $author, $url, $semantics);
}

?>
