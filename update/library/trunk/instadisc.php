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
$instaDisc_subCount = 0;

function instaDisc_sendItem($id, $title, $author, $url, $semantics)
{
	global $idusUsername, $idusPassword, $idusCentralServer, $idusSubscriptionURI;
	$verID = rand(1,65536);

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

function instaDisc_addSubscription($username, $password, $central, $uri, $title, $category, $key = '')
{
	global $instaDisc_subCount, $idusUsername, $idusPassword, $idusCentralServer, $idusSubscriptionURI, $idusSubscriptionTitle, $idusSubscriptionCategory, $idusActivationKey;
	$idusUsername[$instaDisc_subCount] = $username;
	$idusPassword[$instaDisc_subCount] = $password;
	$idusCentralServer[$instaDisc_subCount] = $central;
	$idusSubscriptionURI[$instaDisc_subCount] = $uri;
	$idusSubscriptionTitle[$instaDisc_subCount] = $title;
	$idusSubscriptionCategory[$instaDisc_subCount] = $category;
	$idusActivationKey[$instaDisc_subCount] = $key;
	$instaDisc_subCount++;
}

?>
