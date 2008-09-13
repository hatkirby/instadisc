<?php

/* InstaDisc Update - A Four Island Project */

include('xmlrpc/xmlrpc.inc');

$wgExtensionCredits['other'][] = array(
	'name' => 'InstaDisc',
	'author' => 'Starla Insigna',
	'url' => 'http://fourisland.com/projects/instadisc/wiki/Update/MediaWiki',
	'description' => 'This plugin provides an InstaDisc feed for your MediaWiki wiki, a page-change subscription.'
);

$wgHooks['ArticleSaveComplete'][] = 'instaDisc_sendItem';

function instaDisc_sendItem(&$article, &$user, &$text, &$summary, &$minoredit, &$watchthis, &$sectionanchor, &$flags, &$revision)
{
	global $instaDisc_password, $instaDisc_subscriptionPersonal, $instaDisc_seriesURL, $instaDisc_seriesUsername, $instaDisc_seriesPassword, $instaDisc_subscriptionID, $instaDisc_subscriptionURL, $instaDisc_subscriptionTitle, $instaDisc_subscriptionCategory;

	if (!isset($instaDisc_password) || !isset($instaDisc_subscriptionPersonal) || !isset($instaDisc_seriesURL) || !isset($instaDisc_seriesUsername) || !isset($instaDisc_seriesPassword) || !isset($instaDisc_subscriptionID) || !isset($instaDisc_subscriptionURL) || !isset($instaDisc_subscriptionTitle) || !isset($instaDisc_subscriptionCategory))
	{
		return false;
	}

	$title = $article->getTitle()->getText();
	$author = $user->getName();
	$url = $article->getTitle()->getFullURL();

	$encID = 0;
	if ($instaDisc_password != '')
	{
		$encID = encryptData($title, $author, $url, $instaDisc_password);
	}

	$instaDisc_subscriptionPersonal = ($instaDisc_subscriptionPersonal == 'true' ? 'true' : 'false');

	$verID = rand(1,2147483647);

	$client = new xmlrpc_client($instaDisc_seriesURL);
	$msg = new xmlrpcmsg("InstaDisc.sendFromUpdate", array(	new xmlrpcval($instaDisc_seriesUsername, 'string'),
								new xmlrpcval(md5($instaDisc_seriesUsername . ':' . md5($instaDisc_seriesPassword) . ':' . $verID), 'string'),
								new xmlrpcval($verID, 'int'),
								new xmlrpcval($instaDisc_seriesURL, 'string'),
								new xmlrpcval($instaDisc_subscriptionID, 'string'),
								new xmlrpcval($instaDisc_subscriptionURL, 'string'),
								new xmlrpcval($instaDisc_subscriptionTitle, 'string'),
								new xmlrpcval($instaDisc_subscriptionCategory, 'string'),
								new xmlrpcval($instaDisc_subscriptionPersonal, 'string'),
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
