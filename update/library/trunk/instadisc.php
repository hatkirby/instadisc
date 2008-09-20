<?php

/* InstaDisc Update - A Four Island Project */

include('xmlrpc/xmlrpc.inc');

function instaDisc_sendItem($title, $author, $url, $semantics, $subTitle, $subCategory, $subPassword = '')
{
	$subscriptionURL = 'http://'' . $_SERVER['SERVER_NAME'] . '/' . $subCategory . '/' . generateSlug($subTitle) . '/';

	$encID = 0;
	if (($subPassword != '') && extension_loaded('mcrypt'))
	{
		$encID = rand(1,2147483647);

		$cipher = "rijndael-128";
		$mode = "cbc";
		$key = substr(md5(substr(str_pad($subPassword[$id],16,$encID),0,16)),0,16);

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
	$msg = new xmlrpcmsg("InstaDisc.sendFromUpdate", array(	new xmlrpcval($subscriptionURL, 'string'),
								new xmlrpcval($title, 'string'),
								new xmlrpcval($author, 'string'),
								new xmlrpcval($url, 'string'),
								new xmlrpcval(serialize($semantics), 'string'),
								new xmlrpcval($encID, 'int')));
	$resp = $client->send($msg);
        $val = $resp->value()->scalarVal();

	if ($val == 2)
	{
		return instaDisc_sendItem($subTitle, $subCategory, $title, $author, $url, $semantics);
	} else if ($val == 0)
	{
		return TRUE;
	} else {
		return FALSE;
	}
}

function generateSlug($title)
{
        $title = preg_replace('/[^A-Za-z0-9]/','-',$title);
        $title = preg_replace('/-{2,}/','-',$title);
        if (substr($title,0,1) == '-')
        {
                $title = substr($title,1);
        }
        if (substr($title,strlen($title)-1,1) == '-')
        {
                $title = substr($title,0,strlen($title)-1);
        }
        $title = strtolower($title);

        return($title);
}

function encryptString($td, $key, $string)
{
	mcrypt_generic_init($td, $key, strrev($key));
	$string = bin2hex(mcrypt_generic($td, $string));
	mcrypt_generic_deinit($td);

	return $string;
}

?>
