<?php

/* InstaDisc Server - A Four Island Project */

include('xmlrpc/xmlrpc.inc');
include('xmlrpc/xmlrpcs.inc');
include('db.php');
include('instadisc.php');

function checkRegistration($xmlrpcmsg)
{
	$username = $xmlrpcmsg->getParam(0)->scalarVal();
	$verification = $xmlrpcmsg->getParam(1)->scalarVal();
	$verificationID = $xmlrpcmsg->getParam(2)->scalarVal();

	$getuser = "SELECT * FROM users WHERE username = \"" . $username "\"";
	$getuser2 = mysql_query($getuser):
	$getuser3 = mysql_fetch_array($getuser2);
	if ($getuser3['username'] == $username)
	{
		$test = $username . ":" . $getuser3['password'] . ":" .$verificationID;
		if (md5($test) == $verification)
		{
			return new xmlrpcresp(new xmlrpcval(0, "int"));
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function deleteItem($xmlrpcmsg)
{
	$username = $xmlrpcmsg->getParam(0)->scalarVal();
	$verification = $xmlrpcmsg->getParam(1)->scalarVal();
	$verificationID = $xmlrpcmsg->getParam(2)->scalarVal();
	$id = $xmlrpcmsg->getParam(3)->scalarVal();

	$getuser = "SELECT * FROM users WHERE username = \"" . $username "\"";
	$getuser2 = mysql_query($getuser):
	$getuser3 = mysql_fetch_array($getuser2);
	if ($getuser3['username'] == $username)
	{
		$test = $username . ":" . $getuser3['password'] . ":" .$verificationID;
		if (md5($test) == $verification)
		{
			$getitem = "SELECT * FROM inbox WHERE id = " . $id;
			$getitem2 = mysql_query($getitem);
			$getitem3 = mysql_fetch_array($getitem2);
			if ($getitem3['id'] == $id)
			{
				$delitem = "DELETE inbox WHERE id = " . $id;
				$delitem2 = mysql_query($delitem);

				return new xmlrpcresp(new xmlrpcval(0, "int"));
			}
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function resendItem($xmlrpcmsg)
{
	$username = $xmlrpcmsg->getParam(0)->scalarVal();
	$verification = $xmlrpcmsg->getParam(1)->scalarVal();
	$verificationID = $xmlrpcmsg->getParam(2)->scalarVal();
	$id = $xmlrpcmsg->getParam(3)->scalarVal();

	$getuser = "SELECT * FROM users WHERE username = \"" . $username "\"";
	$getuser2 = mysql_query($getuser):
	$getuser3 = mysql_fetch_array($getuser2);
	if ($getuser3['username'] == $username)
	{
		$test = $username . ":" . $getuser3['password'] . ":" .$verificationID;
		if (md5($test) == $verification)
		{
			$getitem = "SELECT * FROM inbox WHERE id = " . $id;
			$getitem2 = mysql_query($getitem);
			$getitem3 = mysql_fetch_array($getitem2);
			if ($getitem3['id'] == $id)
			{
				instaDisc_sendItem($id);

				return new xmlrpcresp(new xmlrpcval(0, "int"));
			}
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function sendFromUpdate($xmlrpcmsg)
{
	$username = $xmlrpcmsg->getParam(0)->scalarVal();
	$verification = $xmlrpcmsg->getParam(1)->scalarVal();
	$verificationID = $xmlrpcmsg->getParam(2)->scalarVal();
	$subscription = $xmlrpcmsg->getParam(3)->scalarVal();
	$title = $xmlrpcmsg->getParam(4)->scalarVal();
	$author = $xmlrpcmsg->getParam(5)->scalarVal();
	$url = $xmlrpcmsg->getParam(6)->scalarVal();
	$semantics = deserialize($xmlrpcmsg->getParam(7)->serialize());

	$getuser = "SELECT * FROM users WHERE username = \"" . $username . "\"";
	$getuser2 = mysql_query($getuser);
	$getuser3 = mysql_fetch_array($getuser2);
	if ($getuser3['username'] == $username)
	{
		$test = $username . ':' . $getuser3['password'] . ':' . $verificationID;
		if (md5($test) == $verification)
		{
			$getusubs = "SELECT * FROM subscriptions WHERE username = \"" . $username . "\" AND uri = \"" . $subscription . "\" AND owner = \"true\"";
			$getusubs2 = mysql_query($getusubs);
			$getusubs3 = mysql_fetch_array($getusubs2);
			if ($getusubs['username'] == $username)
			{
				$cserver = $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
				$getuk = "SELECT * FROM centralServers WHERE url = \"" . $cserver . "\"";
				$getuk2 = mysql_query($getuk);
				$getuk3 = mysql_fetch_array($getuk2);

				$getcs = "SELECT * FROM centralServers";
				$getcs2 = mysql_query($getcs);
				$i=0;
				while ($getcs3[$i] = mysql_fetch_array($getcs2))
				{
					$verID = rand(1,65536);

					$client = new xmlrpc_client($getcs3[$i]['url']);
					$msg = new xmlrpcmsg("InstaDisc.sendFromCentral", array(	new xmlrpcval($cserver, 'string'),
													new xmlrpcval(md5($cserver + ":" + $getuk3['key'] + ":" + $verID), 'string'),
													new xmlrpcval($verID, 'int'),
													new xmlrpcval($subscription, 'string'),
													new xmlrpcval($title, 'string'),
													new xmlrpcval($author, 'string'),
													new xmlrpcval($url, 'string'),
													new xmlrpcval($semantics, 'array'),
													new xmlrpcval(getConfig('softwareVersion'), 'int'),
													new xmlrpcval(getConfig('databaseVersion'), 'int')));
					$client->send($msg);
					$i++;
				}

				return new xmlrpcresp(new xmlrpcval(0, "int"));
			}
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

function sendFromCentral($xmlrpcmsg)
{
	$cserver = $xmlrpcmsg->getParam(0)->scalarVal();
	$verification = $xmlrpcmsg->getParam(1)->scalarVal();
	$verificationID = $xmlrpcmsg->getParam(2)->scalarVal();
	$subscription = $xmlrpcmsg->getParam(3)->scalarVal();
	$title = $xmlrpcmsg->getParam(4)->scalarVal();
	$author = $xmlrpcmsg->getParam(5)->scalarVal();
	$url = $xmlrpcmsg->getParam(6)->scalarVal();
	$semantics = deserialize($xmlrpcmsg->getParam(7)->serialize());
	$softwareVersion = $xmlrpcmsg->getParam(8)->scalarVal();
	$databaseVersion = $xmlrpcmsg->getParam(9)->scalarVal();

	$getcs = "SELECT * FROM centralServers WHERE url = \"" . $cserver . "\"";
	$getcs2 = mysql_query($getcs);
	$getcs3 = mysql_fetch_array($getcs2);
	if ($getcs3['url'] == $cserver)
	{
		$test = $cserver . ':' . $getcs3['key'] . ':' . $verificationID;
		if (md5($test) == $verification)
		{
			if ($softwareVersion > getConfig('softwareVersion'))
			{
				instaDisc_sendUpdateNotice();
			} else if ($softwareVersion < getConfig('softwareVersion'))
			{
				$cserver2 = $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
				$getuk = "SELECT * FROM centralServers WHERE url = \"" . $cserver2 . "\"";
				$getuk2 = mysql_query($getuk);
				$getuk3 = mysql_fetch_array($getuk2);

				$verID = rand(1,65536);

				$client = new xmlrpc_client($cserver);
				$msg = new xmlrpcmsg("InstaDisc.sendUpdateNotice", array(	new xmlrpcval($cserver2, 'string'),
												new xmlrpcval(md5($cserver2 . ':' . $getuk3['key'] . ':' . $verID), 'string'),
												new xmlrpcval($verID, 'int'),
												new xmlrpcval(getConfig('softwareVersion'), 'int')));
				$client->send($msg);
			}

			if ($databaseVersion > getConfig('databaseVersion'))
			{
				$cserver2 = $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
				$getuk = "SELECT * FROM centralServers WHERE url = \"" . $cserver2 . "\"";
				$getuk2 = mysql_query($getuk);
				$getuk3 = mysql_fetch_array($getuk2);

				$verID = rand(1,65536);

				$client = new xmlrpc_client($cserver);
				$msg = new xmlrpcmsg("InstaDisc.askForDatabase", array(	new xmlrpcval($cserver2, 'string'),
											new xmlrpcval(md5($cserver2 . ':' . $getuk3['key'] . ':' . $verID), 'string'),
											new xmlrpcval($verID, 'int'),
											new xmlrpcval(getConfig('databaseVersion'), 'int')));
				$client->send($msg);
			} else if ($databaseVersion < getConfig('databaseVersion'))
			{
				instaDisc_sendDatabase($cserver);
			}

			$getsed = "SELECT * FROM subscriptions WHERE uri = \"" . $subscription . "\"";
			$getsed2 = mysql_query($getsed);
			$i=0;
			while ($getsed3[$i] = mysql_fetch_array($getsed2))
			{
				instaDisc_addItem($getsed3['username'], $subscription, $title, $author, $url, $semantics);
				$i++;
			}

			return new xmlrpcresp(new xmlrpcval(0, "int"));
		}
	}

	return new xmlrpcresp(new xmlrpcval(1, "int"));
}

$s = new xmlrpc_server(array(	"InstaDisc.checkRegistration" => array("function" => "checkRegistration"),
				"InstaDisc.deleteItem" => array("function" => "deleteItem"),
				"InstaDisc.resendItem" => array("function" => "resendItem"),
				"InstaDisc.requestRetained" => array("function" => "requestRetained"),
				"InstaDisc.sendFromUpdate" => array("function" => "sendFromUpdate"),
				"InstaDisc.sendFromCentral" => array("function" => "sendFromCentral")));

?>
