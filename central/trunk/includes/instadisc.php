<?php

/* InstaDisc Server - A Four Island Project */

include_once('includes/db.php');
include_once('includes/class.phpmailer.php');

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

		$fp = @fsockopen($getuser3['ip'], 1204, $errno, $errstr);
		if ($fp)
		{
			$verID = rand(1,2147483647);

			$title = str_replace(': ', '__INSTADISC__', $getitem3['title']);

			$out = 'ID: ' . $id . "\r\n";
			$out .= 'Verification: ' . md5($username . ':' . $getuser3['password'] . ':' . $verID) . "\r\n";
			$out .= 'Verification-ID: ' . $verID . "\r\n";
			$out .= 'Subscription: ' . $getitem3['subscription'] . "\r\n";
			$out .= 'Title: ' . $title . "\r\n";
			$out .= 'Author: ' . $getitem3['author'] . "\r\n";
			$out .= 'URL: ' . $getitem3['url'] . "\r\n";

			$semantics = unserialize($getitem3['semantics']);
			foreach ($semantics as $name => $value)
			{
				$value = str_replace(': ', '__INSTADISC__', $value);
				$out .= $name . ': ' . $value . "\r\n";
			}

			if ($getitem3['encryptionID'] != 0)
			{
				$out .= 'Encryption-ID: ' . $getitem3['encryptionID'] . "\r\n";
			}

			$out .= "\r\n\r\n";

			fwrite($fp, $out);
			fclose($fp);

			return true;
		} else {
			return false;
		}
	}
}

function instaDisc_sendUpdateNotice($softwareVersion)
{
	$username = instaDisc_getConfig('owner');
	$subscription = 'http://fourisland.com/' . $_SERVER['SERVER_NAME'] . '/';
	$title = 'Update your software to ' . $software;
	$author = 'Hatkirby';
	$url = 'http://fourisland.com/projects/instadisc/wiki/CentralSoftwareUpdate';
	$semantics = array();

	instaDisc_addItem($username, $subscription, $title, $author, $url, $semantics);
}

function instaDisc_sendDatabase($cserver)
{
	$getdb = "SELECT * FROM centralServers";
	$getdb2 = mysql_query($getdb);
	$i=0;
	while ($getdb3[$i] = mysql_fetch_array($getdb2))
	{
		$db[$getdb3[$i]['url']]['code'] = $getdb3[$i]['code'];
		$db[$getdb3[$i]['url']]['xmlrpc'] = $getdb3[$i]['xmlrpc'];
		$i++;
	}

	$cserver2 = $_SERVER['SERVER_NAME'];
	$getuk = "SELECT * FROM centralServers WHERE url = \"" . mysql_real_escape_string($cserver2) . "\"";
	$getuk2 = mysql_query($getuk);
	$getuk3 = mysql_fetch_array($getuk2);

	$verID = rand(1,2147483647);

	$client = new xmlrpc_client($cserver);
	$msg = new xmlrpcmsg("InstaDisc.sendDatabase", array(	new xmlrpcval($cserver2, 'string'),
								new xmlrpcval(md5($cserver2 . ":" . $getuk3['code'] . ":" . $verID), 'string'),
								new xmlrpcval($verID, 'int'),
								new xmlrpcval(serialize($db), 'string'),
								new xmlrpcval(instaDisc_getConfig('databaseVersion'), 'string')));
	$client->send($msg);
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

		instaDisc_sendItem($username, $itemID);
	}
}

function instaDisc_phpMailer()
{
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->From = 'instadisc@' . instaDisc_getConfig('mailDomain');
	$mail->FromName = 'InstaDisc';
	$mail->Host = instaDisc_getConfig('smtpHost');
	if (instaDisc_getConfig('smtpAuth') == 'true')
	{
		$mail->SMTPAuth = true;
		$mail->Username = instaDisc_getConfig('smtpUser');
		$mail->Password = instaDisc_getConfig('smtpPass');
	}
	$mail->Helo = $_SERVER['SERVER_NAME'];
	$mail->ClearAddresses();

	return $mail;
}

function instaDisc_sendActivationEmail($username, $password, $email)
{
	$penKey = md5(rand(1,2147483647));

	$inspending = "INSERT INTO pending (username, password, email, code) VALUES (\"" . mysql_real_escape_string($username) . "\", \"" . mysql_real_escape_string(md5($password)) . "\", \"" . mysql_real_escape_string($email) . "\", \"" . mysql_real_escape_string($penKey) . "\")";
	$inspending2 = mysql_query($inspending);

	$mail = instaDisc_phpMailer();
	$mail->AddAddress($email, $username);
	$mail->Subject = 'InstaDisc Account Verification';
	$mail->Body = "Hello, someone has recently registered an account at " . $_SERVER['SERVER_NAME'] . " with your email address. If that was you, and your chosen username IS " . $username . ", then copy the account verification code below to our Account Verification page, enter your username and press Activate!\r\n\r\n" . $penKey . "\r\n\r\nIf that was not you, copy the above code to our Account Verification page, enter the above username, and click Delete.";
	$mail->Send();

	return ($mail->IsError() ? $mail->ErrorInfo : true);
}

function instaDisc_activateAccount($username, $penKey)
{
	$getuser = "SELECT * FROM pending WHERE username = \"" . mysql_real_escape_string($username) . "\" AND code = \"" . mysql_real_escape_string($penKey) . "\"";
	$getuser2 = mysql_query($getuser);
	$getuser3 = mysql_fetch_array($getuser2);
	if ($getuser3['username'] == $username)
	{
		$insuser = "INSERT INTO users (username, password, email) VALUES (\"" . mysql_real_escape_string($username) . "\", \"" . mysql_real_escape_string($getuser3['password']) . "\", \"" . mysql_real_escape_string($getuser3['email']) . "\")";
		$insuser2 = mysql_query($insuser);

		$delpending = "DELETE FROM pending WHERE username = \"" . mysql_real_escape_string($username) . "\"";
		$delpending2 = mysql_query($delpending);

		$mail = instaDisc_phpMailer();
		$mail->AddAddress($getuser3['email'], $username);
		$mail->Subject = 'Welcome to InstaDisc!';
		$mail->Body = "Welcome to InstaDisc! Thank you for registering at " . instaDisc_getConfig('siteName') . " Central Server, we hope you enjoy our service! Now, when you download an InstaDisc Client, it will ask you for the following information which you will need to enter into it for it to work:\r\n\r\nUsername: " . $username . "\r\nPassword: (you should know this, it's not displayed here for security reasons)\r\nCentral Server URL: " . instaDisc_getConfig("xmlrpcURL") . "\r\n\r\nOnce again, thank you for choosing " . instaDisc_getConfig("siteName") . "!";
		$mail->Send();

		return ($mail->IsError() ? $mail->ErrorInfo : true);
	} else {
		return false;
	}
}

function instaDisc_deactivateAccount($username, $penKey)
{
	$getuser = "SELECT * FROM pending WHERE username = \"" . mysql_real_escape_string($username) . "\" AND code = \"" . mysql_real_escape_string($penKey) . "\"";
	$getuser2 = mysql_query($getuser);
	$getuser3 = mysql_fetch_array($getuser2);
	if ($getuser3['username'] == $username)
	{
		$delpending = "DELETE FROM pending WHERE username = \"" . mysql_real_escape_string($username) . "\"";
		$delpending2 = mysql_query($delpending);

		return true;
	} else {
		return false;
	}
}

function instaDisc_verifyUser($username, $password)
{
	$getuser = "SELECT * FROM users WHERE username = \"" . mysql_real_escape_string($username). "\" AND password = \"" . mysql_real_escape_string(md5($password)) . "\"";
	$getuser2 = mysql_query($getuser);
	$getuser3 = mysql_fetch_array($getuser2);

	return ($getuser3['username'] == $username);
}

function instaDisc_deleteAccount($username)
{
	$getuser = "SELECT * FROM users WHERE username = \"" . mysql_real_escape_string($username) . "\"";
	$getuser2 = mysql_query($getuser);
	$getuser3 = mysql_fetch_array($getuser2);
	if ($getuser3['username'] == $username)
	{
		$deluser = "DELETE FROM users WHERE username = \"" . mysql_real_escape_string($username) . "\"";
		$deluser2 = mysql_query($deluser);

		$delsubs = "DELETE FROM subscriptions WHERE username = \"" . mysql_real_escape_string($username) . "\"";
		$delsubs2 = mysql_query($delsubs);

		$delitems = "DELETE FROM inbox WHERE username = \"" . mysql_real_escape_string($username) . "\"";
		$delitems2 = mysql_query($delitems);

		return true;
	}

	return false;
}

function instaDisc_getConfig($key)
{
	$getconfig = "SELECT * FROM config WHERE name = \"" . mysql_real_escape_string($key) . "\"";
	$getconfig2 = mysql_query($getconfig);
	$getconfig3 = mysql_fetch_array($getconfig2);

	return $getconfig3['value'];
}

function instaDisc_listSubscriptions($username)
{
	$getsubs = "SELECT * FROM subscriptions WHERE username = \"" . mysql_real_escape_string($username) . "\" AND owner = \"true\"";
	$getsubs2 = mysql_query($getsubs);
	$i=0;
	while ($getsubs3[$i] = mysql_fetch_array($getsubs2))
	{
		$subs[$i] = $getsubs3[$i]['url'];

		$i++;
	}

	$subs['size'] = $i;
	return $subs;
}

function instaDisc_addSubscription($username, $url)
{
	$getcode = "SELECT * FROM pending2 WHERE username = \"" . mysql_real_escape_string($username) . "\" AND url = \"" . mysql_real_escape_string($url) . "\"";
	$getcode2 = mysql_query($getcode);
	$getcode3 = mysql_fetch_array($getcode2);
	if ($getcode3['username'] == $username)
	{
		$delcode = "DELETE FROM pending2 WHERE username = \"" . mysql_real_escape_string($username) . "\" AND url = \"" . mysql_real_escape_string($url) . "\"";
		$delcode2 = mysql_query($delcode);

		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_HEADER, false);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		$page_data = curl_exec($c);
		curl_close($c);

		$headers = split("\n", $page_date);
		foreach ($headers as $name => $value)
		{
			$header = split(": ", $value);
			$headerMap[$header[0]] = $header[1];
		}

		if (isset($header['Subscription']))
		{
			if (isset($header['Title']))
			{
				if (isset($header['Category']))
				{
					if (isset($header['Key']))
					{
						if ($header['Key'] == $getcode3['code'])
						{
							$inssub = "INSERT INTO subscriptions (username,url,owner,category) VALUES (\"" . mysql_real_escape_string($username) . "\", \"" . mysql_real_escape_string($header['Subscription']) . "\", \"true\", \"" . mysql_real_escape_string($header['Category']) . "\")";
							$inssub2 = mysql_query($inssub);

							return true;
						}
					}
				}
			}
		}
	}

	return false;
}

function instaDisc_listPendingSubscriptions($username)
{
	$getsubs = "SELECT * FROM pending2 WHERE username = \"" . mysql_real_escape_string($username) . "\"";
	$getsubs2 = mysql_query($getsubs);
	$i=0;
	while ($getsubs3[$i] = mysql_fetch_array($getsubs2))
	{
		$subs[$i] = array('url' => $getsubs3[$i]['url'], 'code' => $getsubs3[$i]['code']);

		$i++;
	}

	$subs['size'] = $i;
	return $subs;
}

function instaDisc_generateSubscriptionActivation($username, $url)
{
	$getuser = "SELECT * FROM users WHERE username = \"" . mysql_real_escape_string($username) . "\"";
	$getuser2 = mysql_query($getuser);
	$getuser3 = mysql_fetch_array($getuser2);
	if ($getuser3['username'] == $username)
	{
		$key = md5(rand(1,2147483647));

		$inspending = "INSERT INTO pending2 (username, url, code) VALUES (\"" . mysql_real_escape_string($username) . "\", \"" . mysql_real_escape_string($url) . "\", \"" . mysql_real_escape_string($key) . "\")";
		$inspending2 = mysql_query($inspending);

		return $key;
	}

	return false;
}

function instaDisc_deleteSubscription($username, $url)
{
	$getsub = "SELECT * FROM subscriptions WHERE username = \"" . mysql_real_escape_string($username) . "\" AND url = \"" . mysql_real_escape_string($url) . "\")";
	$getsub2 = mysql_query($getsub);
	$getsub3 = mysql_fetch_array($getsub2);
	if ($getsub3['username'] == $username)
	{
		$delsub = "DELETE FROM subscriptions WHERE username = \"" . mysql_real_escape_string($username) . "\" AND url = \"" . mysql_real_escape_string($url) . "\")";
		$delsub2 = mysql_query($delsub);

		return true;
	}

	return false;
}

function instaDisc_cancelSubscription($username, $url)
{
	$getsub = "SELECT * FROM pending2 WHERE username = \"" . mysql_real_escape_string($username) . "\" AND url = \"" . mysql_real_escape_string($url) . "\")";
	$getsub2 = mysql_query($getsub);
	$getsub3 = mysql_fetch_array($getsub2);
	if ($getsub3['username'] == $username)
	{
		$delsub = "DELETE FROM pending2 WHERE username = \"" . mysql_real_escape_string($username) . "\" AND url = \"" . mysql_real_escape_string($url) . "\")";
		$delsub2 = mysql_query($delsub);

		return true;
	}

	return false;
}

function instaDisc_changePassword($username, $password)
{
	$setpass = "UPDATE users WHERE username = \"" . mysql_real_escape_string($username) . "\" SET password = \"" . mysql_real_escape_string(md5($password)) . "\"";
	$setpass2 = mysql_query($setpass);
}

?>
