<?php

/* InstaDisc Update - A Four Island Project */

include('xmlrpc/xmlrpc.inc');

$wgExtensionCredits['other'][] = array(
	'name' => 'InstaDisc',
	'author' => 'Starla Insigna',
	'url' => 'http://instadisc.org/MediaWiki',
	'description' => 'This plugin provides an InstaDisc feed for your MediaWiki wiki, a page-change subscription.'
);

$wgHooks['ArticleSaveComplete'][] = 'instaDisc_sendItem';

function instaDisc_sendItem(&$article, &$user, &$text, &$summary, &$minoredit, &$watchthis, &$sectionanchor, &$flags, &$revision)
{
	global $instaDisc_title, $instaDisc_password;

	if (!isset($instaDisc_title))
	{
		return false;
	}

	$title = $article->getTitle()->getText();
	$author = $user->getName();
	$url = $article->getTitle()->getFullURL();

	$subscriptionURL = 'http://' . $_SERVER['SERVER_NAME'] . '/page-change/' . generateSlug($instaDisc_title) . '/';

	$encID = 0;
	if ((!isset($instaDisc_password) && ($instaDisc_password != '')) && (extension_loaded('mcrypt')))
	{
		$encID = encryptData($title, $author, $url, $instaDisc_password);
	}

	$verID = rand(1,2147483647);

	$client = new xmlrpc_client('http://rpc.instadisc.org');
	$msg = new xmlrpcmsg("InstaDisc.sendFromUpdate", array(	new xmlrpcval($subscriptionURL, 'string'),
								new xmlrpcval($title, 'string'),
								new xmlrpcval($author, 'string'),
								new xmlrpcval($url, 'string'),
								new xmlrpcval(serialize(array()), 'string'),
								new xmlrpcval($encID, 'int')));
	$resp = $client->send($msg);
	$val = $resp->value()->scalarVal();

	if ($val == 2)
	{
		instaDisc_sendItem($article, $user, $text, $summary, $minoredit, $watchthis, $sectionanchor, $flags, $revision);
	} else if ($val == 0)
	{
		return true;
	} else {
		return false;
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

function encryptData(&$title, &$author, &$url, $password)
{
        $encID = rand(1,2147483647);

        $cipher = "rijndael-128";
        $mode = "cbc";
        $key = substr(md5(substr(str_pad($password,16,$encID),0,16)),0,16);

        $td = mcrypt_module_open($cipher, "", $mode, "");

        $title = encryptString($td, $key, $title);
        $author = encryptString($td, $key, $author);
        $url = encryptString($td, $key, $url);

        mcrypt_module_close($td);

        return $encID;
}

function encryptString($td, $key, $string)
{
        mcrypt_generic_init($td, $key, strrev($key));
        $string = bin2hex(mcrypt_generic($td, $string));
        mcrypt_generic_deinit($td);

        return $string;
}
