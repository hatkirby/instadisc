<?php

/* InstaDisc Update - A Four Island Project */

include('xmlrpc/xmlrpc.inc');

$subTitle = $_SERVER['argv'][1];
$author = $_SERVER['argv'][2];
$title = $_SERVER['argv'][3];
$rev = $_SERVER['argv'][4];

$argToWrite = parseArg(5,6);
if ($argToWrite != '')
{
	$$argToWrite = $_SERVER['argv'][6];
}

$argToWrite = parseArg(7,8);
if ($argToWrite != '')
{
	$$argToWrite = $_SERVER['argv'][8];
}

if (!isset($urlScheme))
{
	$urlScheme = '';
}

if (!isset($subPassword))
{
	$subPassword = '';
}

instaDisc_sendItem($title, $author, str_replace('REV', $rev, $urlScheme), array(), $subTitle, $subPassword);

function instaDisc_sendItem($title, $author, $url, $semantics, $subTitle, $subPassword = '')
{
	$subscriptionURL = 'http://' . $_SERVER['SERVER_NAME'] . '/vcs-rev/' . generateSlug($subTitle) . '/';

	$encID = 0;
	if (($subPassword != '') && extension_loaded('mcrypt'))
	{
		$encID = rand(1,2147483647);

		$cipher = "rijndael-128";
		$mode = "cbc";
		$key = substr(md5(substr(str_pad($subPassword,16,$encID),0,16)),0,16);

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

	$client = new xmlrpc_client('http://rpc.instadisc.org');
	$msg = new xmlrpcmsg("InstaDisc.sendFromUpdate", array(	new xmlrpcval($subscriptionURL, 'string'),
								new xmlrpcval($title, 'string'),
								new xmlrpcval($author, 'string'),
								new xmlrpcval($url, 'string'),
								new xmlrpcval(serialize($semantics), 'string'),
								new xmlrpcval($encID, 'int')));
	$client->setDebug(4);
	$resp = $client->send($msg);
        $val = $resp->value()->scalarVal();

	if ($val == 2)
	{
		return instaDisc_sendItem($title, $author, $url, $semantics, $subTitle, $subPassword);
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

function parseArg($switch, $value)
{
	if ((!isset($_SERVER['argv'][$switch])) || (!isset($_SERVER['argv'][$value])))
	{
		return '';
	}

	if ($_SERVER['argv'][$switch] == '-u')
	{
		return 'urlScheme';
	} else if ($_SERVER['argv'][$switch] == '-p')
	{
		return 'subPassword';
	} else {
		return '';
	}
}

?>
