<?php

/* InstaDisc Update - A Four Island Project */

include('xmlrpc/xmlrpc.inc');

$idusUsername = array();
$idusPassword = array();
$idusCentralServer = array();
$idusSubscriptionURI = array();
$idusSubscriptionTitle = array();
$idusSubscriptionCategory = array();
$idusActivationKey = array();
$idusEncryptionKey = array();
$instaDisc_subCount = 0;

function instaDisc_sendItem($id, $title, $author, $url, $semantics, $verID = 0)
{
	global $idusUsername, $idusPassword, $idusCentralServer, $idusSubscriptionURI;

	if ($verID == 0)
	{
		$verID = rand(1,65536);
	}

	$client = new xmlrpc_client($idusCentralServer[$id]);
	$msg = new xmlrpcmsg("InstaDisc.sendFromUpdate", array(	new xmlrpcval($idusUsername[$id], 'string'),
								new xmlrpcval(md5($idusUsername[$id] . ":" . md5($idusPassword[$id]) . ":" . $verID), 'string'),
								new xmlrpcval($verID, 'int'),
								new xmlrpcval($idusSubscriptionURI[$id], 'string'),
								new xmlrpcval($title, 'string'),
								new xmlrpcval($author, 'string'),
								new xmlrpcval($url, 'string'),
								new xmlrpcval(serialize($semantics), 'string')));
	$client->send($msg);
}

function instaDisc_sendEncrypted($id, $title, $author, $url, $semantics)
{
	global $idusEncryptionKey;

	$verID = 0;
	while ($verID == 0)
	{
		$verID = rand(1,65536);
	}

	$cipher = "rijndael-128";
	$mode = "cbc";
	$key = substr(md5(substr(str_pad($idusEncryptionKey[$id],16,$verID),0,16)),0,16);

	$td = mcrypt_module_open($cipher, "", $mode, "");
	mcrypt_generic_init($td, $key, strrev($key));
	$title = bin2hex(mcrypt_generic($td, $title));
	$author = bin2hex(mcrypt_generic($td, $author));
	$url = bin2hex(mcrypt_generic($td, $url));

	foreach ($semantics as $name => $value)
	{
		$semantics[$name] = bin2hex(mcrypt_generic($td, $value));
	}
	
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);

	instaDisc_sendItem($id, $title, $author, $url, $semantics, $verID);
}

function instaDisc_addSubscription($username, $password, $central, $uri, $title, $category, $key = '', $enc = '')
{
	global $instaDisc_subCount, $idusUsername, $idusPassword, $idusCentralServer, $idusSubscriptionURI, $idusSubscriptionTitle, $idusSubscriptionCategory, $idusActivationKey, $idusEncryptionKey;
	$idusUsername[$instaDisc_subCount] = $username;
	$idusPassword[$instaDisc_subCount] = $password;
	$idusCentralServer[$instaDisc_subCount] = $central;
	$idusSubscriptionURI[$instaDisc_subCount] = $uri;
	$idusSubscriptionTitle[$instaDisc_subCount] = $title;
	$idusSubscriptionCategory[$instaDisc_subCount] = $category;
	$idusActivationKey[$instaDisc_subCount] = $key;
	$idusEncryptionKey[$instaDisc_subCount] = $enc;
	$instaDisc_subCount++;
}

?>
