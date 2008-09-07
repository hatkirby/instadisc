<?php

/* InstaDisc Update - A Four Island Project */

include('xmlrpc/xmlrpc.inc');

$idusSubscriptionSeriesURL = array();
$idusSubscriptionID = array();
$idusSubscriptionTitle = array();
$idusEncryptionKey = array();
$instaDisc_subCount = 0;

function instaDisc_sendItem($id, $title, $author, $url, $semantics)
{
	global $idusSubscriptionSeriesURL, $idusSubscriptionID, $idusEncryptionKey;

	$encID = 0;
	if (($idusEncryptionKey[$id] != '') && extension_loaded('mcrypt'))
	{
		$encID = rand(1,2147483647);

		$cipher = "rijndael-128";
		$mode = "cbc";
		$key = substr(md5(substr(str_pad($idusEncryptionKey[$id],16,$encID),0,16)),0,16);

		$td = mcrypt_module_open($cipher, "", $mode, "");

		$title = encryptString($td, $key, $title);
		$author = encryptString($td, $key, $author);
		$url = encryptString($td, $key, $url);

		foreach ($semantics as $name => $value)
		{
			$semantics[$name] = encryptString($td, $key, $value);
		}
	
		mcrypt_module_close($td);
	}
	
	$verID = rand(1,2147483647);

	$client = new xmlrpc_client('http://central.fourisland.com/xmlrpc.php');
	$msg = new xmlrpcmsg("InstaDisc.sendFromUpdate", array( new xmlrpcval($idusSubscriptionSeriesURL[$id], 'string'),
								new xmlrpcval($idusSubscriptionID[$id], 'string'),
								new xmlrpcval($title, 'string'),
								new xmlrpcval($author, 'string'),
								new xmlrpcval($url, 'string'),
								new xmlrpcval(serialize($semantics), 'string'),
								new xmlrpcval($encID, 'int')));
	$resp = $client->send($msg);
        $val = $resp->value()->scalarVal();

	if ($val == 2)
	{
		return instaDisc_sendItem($id, $title, $author, $url, $semantics, $encryptionID);
	} else if ($val == 0)
	{
		return TRUE;
	} else {
		return FALSE;
	}
}

function instaDisc_addSubscription($url, $id, $title, $enc = '')
{
	global $instaDisc_subCount, $idusSubscriptionSeriesURL, $idusSubscriptionID, $idusSubscriptionTitle, $idusEncryptionKey;
	$idusSubscriptionSeriesURL[$instaDisc_subCount] = $url;
	$idusSubscriptionID[$instaDisc_subCount] = $id;
	$idusSubscriptionTitle[$instaDisc_subCount] = $title;
	$idusEncryptionKey[$instaDisc_subCount] = $enc;
	$instaDisc_subCount++;
}

function encryptString($td, $key, $string)
{
	mcrypt_generic_init($td, $key, strrev($key));
	$string = bin2hex(mcrypt_generic($td, $string));
	mcrypt_generic_deinit($td);

	return $string;
}

?>
