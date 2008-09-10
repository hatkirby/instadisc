<?php

/* InstaDisc Update - A Four Island Project */

include('xmlrpc/xmlrpc.inc');

$idusSubscriptionSeriesURL = array();
$idusSubscriptionID = array();
$idusSeriesUsername = array();
$idusSeriesPassword = array();
$idusSubscriptionURL = array();
$idusSubscriptionCategory = array();
$idusSubscriptionPersonal = array();
$idusEncryptionKey = array();
$instaDisc_subCount = 0;

function instaDisc_sendItem($id, $title, $author, $url, $semantics)
{
	global $idusSubscriptionSeriesURL, $idusSubscriptionID, $idusSeriesUsername, $idusSeriesPassword, $idusSubscriptionURL, $idusSubscriptionCategory, $idusSubscriptionPersonal, $idusEncryptionKey;

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

	$client = new xmlrpc_client($idusSubscriptionSeriesURL[$id]);
	$msg = new xmlrpcmsg("InstaDisc.sendFromUpdate", array(	new xmlrpcval($idusSeriesUsername[$id], 'string'),
								new xmlrpcval(md5($idusSeriesUsername[$id] . ':' . md5($idusSeriesPassword[$id]) . ':' . $verID), 'string'),
								new xmlrpcval($verID, 'int'),
								new xmlrpcval($idusSubscriptionSeriesURL[$id], 'string'),
								new xmlrpcval($idusSubscriptionID[$id], 'string'),
								new xmlrpcval($idusSubscriptionURL[$id], 'string'),
								new xmlrpcval($idusSubscriptionTitle[$id], 'string'),
								new xmlrpcval($idusSubscriptionCategory[$id], 'string'),
								new xmlrpcval($idusSubscriptionPersonal[$id], 'string'),
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

function instaDisc_addSubscription($url, $id, $un, $pw, $sUrl, $cat, $personal = '', $enc = '')
{
	global $instaDisc_subCount, $idusSubscriptionSeriesURL, $idusSubscriptionID, $idusSeriesUsername, $idusSeriesPassword, $idusSubscriptionURL, $idusSubscriptionCategory, $idusSubscriptionPersonal, $idusEncryptionKey;
	$idusSubscriptionSeriesURL[$instaDisc_subCount] = $url;
	$idusSubscriptionID[$instaDisc_subCount] = $id;
	$idusSeriesUsername[$instaDisc_subCount] = $un;
	$idusSeriesPassword[$instaDisc_subCount] = $pw;
	$idusSubscriptionURL[$instaDisc_subCount] = $sUrl;
	$idusSubscriptionCategory[$instaDisc_subCount] = $cat;
	$idusSubscriptionPersonal[$instaDisc_subCount] = ($personal != '' ? 'true' : 'false');
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
