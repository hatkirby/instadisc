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

$s = new xmlrpc_server(array(
			"InstaDisc.checkRegistration" => array("function" => "checkRegistration"),
			"InstaDisc.deleteItem" => array("function" => "deleteItem"),
			"InstaDisc.resendItem" => array("function" => "resendItem"),
			"InstaDisc.requestRetained" => array("function" => "requestRetained")
		));

?>
