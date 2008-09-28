<?php

/* InstaDisc Server - A Four Island Project */

include_once('includes/db.php');

function instaDisc_checkVerification($username, $verification, $verificationID, $table, $nameField, $passField)
{
	$getverid = "SELECT * FROM oldVerID WHERE username = \"" . mysql_real_escape_string($username) . "\" AND verID = " . $verificationID;
	$getverid2 = mysql_query($getverid);
	$getverid3 = mysql_fetch_array($getverid2);
	if ($getverid3['id'] != $verificationID)
	{
		$getitem = "SELECT * FROM " . $table . " WHERE " . $nameField . " = \"" . mysql_real_escape_string($username) . "\"";
		$getitem2 = mysql_query($getitem);
		$getitem3 = mysql_fetch_array($getitem2);
		if ($getitem3[$nameField] == $username)
		{
			$test = $username . ':' . $getitem3[$passField] . ':' . $verificationID;

			if (md5($test) == $verification)
			{
				$cntverid = "SELECT COUNT(*) FROM oldVerID WHERE username = \"" . mysql_real_escape_string($username) . "\"";
				$cntverid2 = mysql_query($cntverid);
				$cntverid3 = mysql_fetch_array($cntverid2);
				if ($cntverid3[0] >= intval(instaDisc_getConfig('verIDBufferSize')))
				{
					$delverid = "DELETE FROM oldVerID WHERE username = \"" . mysql_real_escape_string($username) . "\" LIMIT 0,1";
					$delverid2 = mysql_query($delverid);
				}

				$insverid = "INSERT INTO oldVerID (username, verID) VALUES (\"" . mysql_real_escape_string($username) . "\", " . $verificationID . ")";
				$insverid2 = mysql_query($insverid);

				if (($table == 'users') && ($getitem3['ip'] != $_SERVER['REMOTE_ADDR']))
				{
					$setuser = "UPDATE users SET ip = \"" . $_SERVER['REMOTE_ADDR'] . "\" WHERE id = " . $getitem3['id'];
					$setuser2 = mysql_query($setuser);
				}

				return true;
			}
		}
	}

	return false;
}

function instaDisc_sendItem($username, $id)
{
	$getitem = "SELECT * FROM inbox WHERE username = \"" . mysql_real_escape_string($username) . "\" AND itemID = " . $id;
	$getitem2 = mysql_query($getitem);
	$getitem3 = mysql_fetch_array($getitem2);
	if ($getitem3['username'] == $username)
	{
		$getuser = "SELECT * FROM users WHERE username = \"" . mysql_real_escape_string($username) . "\"";
		$getuser2 = mysql_query($getuser);
		$getuser3 = mysql_fetch_array($getuser2);

		if (($getuser3['downloadItemMode'] == 'Push') && ($getuser3['port'] != 0))
		{
			$fp = @fsockopen($getuser3['ip'], $getuser3['port'], $errno, $errstr);
			if ($fp)
			{
				$title = str_replace(': ', '__INSTADISC__', $getitem3['title']);

				$out = instaDisc_formItem($username, $id, "\r\n") . "\r\n\r\n";

				fwrite($fp, $out);
				fclose($fp);

				return true;
			} else {
				return false;
			}
		}
	}
}

function instaDisc_addItem($username, $subscription, $title, $author, $url, $semantics, $encryptionID = 0)
{
	$getuser = "SELECT * FROM users WHERE username = \"" . mysql_real_escape_string($username) . "\"";
	$getuser2 = mysql_query($getuser);
	$getuser3 = mysql_fetch_array($getuser2);
	if ($getuser3['username'] == $username)
	{
		$itemID = $getuser3['nextItemID'];
		$setuser = "UPDATE users SET nextItemID = nextItemID+1 WHERE username = \"" . mysql_real_escape_string($username) . "\"";
		$setuser2 = mysql_query($setuser);

		$insitem = "INSERT INTO inbox (username, itemID, subscription, title, author, url, semantics, encryptionID) VALUES (\"" . mysql_real_escape_string($username) . "\", " . $itemID . ", \"" . mysql_real_escape_string($subscription) . "\", \"" . mysql_real_escape_string($title) . "\", \"" . mysql_real_escape_string($author) . "\", \"" . mysql_real_escape_string($url) . "\", \"" . mysql_real_escape_string($semantics) . "\"," . $encryptionID . ")";
		$insitem2 = mysql_query($insitem);

		if ($getuser3['downloadItemMode'] == 'Push')
		{
			instaDisc_sendItem($username, $itemID);
		}
	}
}

function instaDisc_createUser($username, $password)
{
	$insuser = "INSERT INTO users (username, password) VALUES (\"" . mysql_real_escape_string($username) . "\", \"" . mysql_real_escape_string($password) . "\")";
	$insuser2 = mysql_query($insuser);
}

function instaDisc_getConfig($key)
{
	$getconfig = "SELECT * FROM config WHERE name = \"" . mysql_real_escape_string($key) . "\"";
	$getconfig2 = mysql_query($getconfig);
	$getconfig3 = mysql_fetch_array($getconfig2);

	return $getconfig3['value'];
}

function instaDisc_changePassword($username, $password)
{
	$setpass = "UPDATE users WHERE username = \"" . mysql_real_escape_string($username) . "\" SET password = \"" . mysql_real_escape_string(md5($password)) . "\"";
	$setpass2 = mysql_query($setpass);
}

function instaDisc_initalizePort($username)
{
	$getports = "SELECT * FROM users WHERE ip = \"" . mysql_real_escape_string($username) . "\" AND port <> 0 ORDER BY port ASC";
	$getports2 = mysql_query($getports);
	$i=0;
	while ($getports3[$i] = mysql_fetch_array($getports2))
	{
		$i++;
	}

	if ($i==0)
	{
		$port = 1204;
	} else if ($i>=4331)
	{
		return 0;
	} else {
		$port = (61204 + ($i-1));
	}

	$setuser = "UPDATE users SET port = " . $port . " WHERE username = \"" . mysql_real_escape_string($username) . "\"";
	$setuser2 = mysql_query($setuser);

	return $port;
}

function instaDisc_formItem($username, $id, $ln = "\n")
{
	$getitem = "SELECT * FROM inbox WHERE username = \"" . mysql_real_escape_string($username) . "\" AND itemID = " . $id;
	$getitem2 = mysql_query($getitem);
	$getitem3 = mysql_fetch_array($getitem2);
	if ($getitem3['username'] == $username)
	{
		$getuser = "SELECT * FROM users WHERE username = \"" . mysql_real_escape_string($username) . "\"";
		$getuser2 = mysql_query($getuser);
		$getuser3 = mysql_fetch_array($getuser2);

		$verID = rand(1,2147483647);

		$out = 'ID: ' . $id . $ln;
		$out .= 'Verification: ' . md5($username . ':' . $getuser3['password'] . ':' . $verID) . $ln;
		$out .= 'Verification-ID: ' . $verID . $ln;
		$out .= 'Subscription: ' . $getitem3['subscription'] . $ln;
		$out .= 'Title: ' . $title . $ln;
		$out .= 'Author: ' . $getitem3['author'] . $ln;
		$out .= 'URL: ' . $getitem3['url'] . $ln;

		$semantics = unserialize($getitem3['semantics']);
		foreach ($semantics as $name => $value)
		{
			$value = str_replace(': ', '__INSTADISC__', $value);
			$out .= $name . ': ' . $value . $ln;
		}
		if ($getitem3['encryptionID'] != 0)
		{
			$out .= 'Encryption-ID: ' . $getitem3['encryptionID'] . $ln;
		}
	}
}

?>
