<?php

/* InstaDisc Server - A Four Island Project */

include('includes/class.phpmailer.php');

$softwareVersion = 1;

if (!isset($_GET['submit']))
{
	showHeader('1');
	showStepOne('localhost', 'root', '', 'instadisc', array());
} else {
	$numOfErrors = 0;
	$errors = array();

	switch ($_GET['submit'])
	{
		case 1:
			if ($_POST['host'] == '')
			{
				addError($numOfErrors, $errors, 'host', 'Hostname is a required field');
			}

			if ($_POST['username'] == '')
			{
				addError($numOfErrors, $errors, 'username', 'Username is a required field');
			}

			if ($_POST['password'] == '')
			{
				addError($numOfErrors, $errors, 'password', 'Password is a required field');
			}

			if ($_POST['dbname'] == '')
			{
				addError($numOfErrors, $errors, 'dbname', 'Name is a required field');
			}

			if ($numOfErrors > 0)
			{
				showHeader('1');
				showStepOne($_POST['host'], $_POST['username'], $_POST['password'], $_POST['dbname'], $errors);
			} else {
				if (!@mysql_connect($_POST['host'], $_POST['username'], $_POST['password']))
				{
					addError($numOfErrors, $errors, '', 'Cannot connect to database server');
					showHeader('1');
					showStepOne($_POST['host'], $_POST['username'], $_POST['password'], $_POST['dbname'], $errors);
				} else {
					if (!@mysql_select_db($_POST['dbname']))
					{
						addError($numOfErrors, $errors, 'dbname', 'Database does not exist');
						showHeader('1');
						showStepOne($_POST['host'], $_POST['username'], $_POST['password'], $_POST['dbname'], $errors);
					} else {
						$sql = file_get_contents('instadisc.sql');
						$makedb = split(';', $sql);
						foreach ($makedb as $name => $value)
						{
							if (!trim($value) == '')
							{
								$makedb2 = @mysql_query($value);
								if (!$makedb2)
								{
									addError($numOfErrors, $errors, '', "MySQL error \"" . mysql_error() . "\" while creating database");
								}
							}
						}

						@file_put_contents('includes/config.php', "<?php\n\n/* InstaDisc Server - A Four Island Project */\n\n\$dbhost = \"" . $_POST['host'] . "\";\n\$dbuser = \"" . $_POST['username'] . "\";\n\$dbpass = \"" . $_POST['password'] . "\";\n\$dbname = \"" . $_POST['dbname'] . "\";\n\n?>");

						if (!file_exists('includes/config.php'))
						{
							addError($numOfErrors, $errors, '', 'Could not write config.php file, please check directory permissions');
						}

						if ($numOfErrors > 0)
						{
							showHeader('1');
							showStepOne($_POST['host'], $_POST['username'], $_POST['password'], $_POST['dbname'], $errors);
						} else {
							showHeader('2');
							showStepTwo('','',' CHECKED','','','','','','','', array());
						}
					}
				}
			}

			break;

		case 2:
			if ($_POST['smtpHost'] == '')
			{
				addError($numOfErrors, $errors, 'smtpHost', 'SMTP Host is a required field');
			}

			if ($_POST['smtpAuth'] == 'on')
			{
				if ($_POST['smtpUser'] == '')
				{
					addError($numOfErrors, $errors, 'smtpUser', 'When "SMTP Authentication Required?" is checked, SMTP Username is a required field');
				}

				if ($_POST['smtpPass'] == '')
				{
					addError($numOfErrors, $errors, 'smtpPass', 'When "SMTP Authentication Required?" is checked, SMTP Password is a required field');
				}
			}

			if ($numOfErrors > 0)
			{
				showHeader('2');
				showStepTwo($_POST['mailDomain'], $_POST['smtpHost'], ($_POST['smtpAuth'] == 'on' ? ' CHECKED' : ''), $_POST['smtpUser'], $_POST['smtpPass'], $_POST['siteName'], $_POST['xmlrpcURL'], $_POST['adminUser'], $_POST['adminPass'], $_POST['adminEmail'], $errors);
			} else {
			        $mail = new PHPMailer();
			        $mail->IsSMTP();
			        $mail->From = 'instadisc@' . $_POST['mailDomain'];
			        $mail->FromName = 'InstaDisc';
			        $mail->Host = $_POST['smtpHost'];
			        if ($_POST['smtpAuth'] == 'on')
			        {
			                $mail->SMTPAuth = true;
			                $mail->Username = $_POST['smtpUser'];
			                $mail->Password = $_POST['smtpPass'];
			        }
			        $mail->Helo = $_SERVER['HTTP_HOST'];
			        $mail->ClearAddresses();
				$mail->AddAddress("test@fourisland.com");
				$mail->Subject = 'Test Email';
				$mail->Body = 'Please discard this email.';
				$mail->Send();
				if ($mail->IsError())
				{
					addError($numOfErrors, $errors, '', $mail->ErrorInfo);
				}

				if ($_POST['mailDomain'] == '')
				{
					addError($numOfErrors, $errors, 'mailDomain', 'Mail Domain is a required field');
				}

				if ($_POST['siteName'] == '')
				{
					addError($numOfErrors, $errors, 'siteName', 'Site Name is a required field');
				}

				if ($_POST['xmlrpcURL'] == '')
				{
					addError($numOfErrors, $errors, 'xmlrpcURL', 'XML-RPC URL is a required field');
				} else {
					include_once('includes/xmlrpc/xmlrpc.inc');

					$client = new xmlrpc_client($_POST['xmlrpcURL']);
					$msg = new xmlrpcmsg('system.listMethods');
					$r = $client->send($msg);
					if (stripos($r->faultString(),'Connect error') !== FALSE)
					{
						addError($numOfErrors, $errors, 'xmlrpcURL', $r->faultString());
					}
				}

				if ($_POST['adminUser'] == '')
				{
					addError($numOfErrors, $errors, 'adminUser', 'Admin Username is a required field');
				}

				if ($_POST['adminPass'] == '')
				{
					addError($numOfErrors, $errors, 'adminPass', 'Admin Password is a required field');
				}

				if ($_POST['adminEmail'] == '')
				{
					addError($numOfErrors, $errors, 'adminEmail', 'Admin Email is a required field');
				}

				if ($numOfErrors > 0)
				{
					showHeader('2');
					showStepTwo($_POST['mailDomain'], $_POST['smtpHost'], ($_POST['smtpAuth'] == 'on' ? ' CHECKED' : ''), $_POST['smtpUser'], $_POST['smtpPass'], $_POST['siteName'], $_POST['xmlrpcURL'], $_POST['adminUser'], $_POST['adminPass'], $_POST['adminEmail'], $errors);
				} else {
					include_once('config.php');

					mysql_connect($dbhost, $dbuser, $dbpass);
					mysql_select_db($dbname);

					$sql[0] = "INSERT INTO config (name,value) VALUES (\"mailDomain\",\"" . mysql_real_escape_string($_POST['mailDomain']) . "\")";
					$sql[1] = "INSERT INTO config (name,value) VALUES (\"smtpHost\",\"" . mysql_real_escape_string($_POST['smtpHost']) . "\")";
					$sql[2] = "INSERT INTO config (name,value) VALUES (\"smtpAuth\",\"" . mysql_real_escape_string(($_POST['smtpAuth'] == 'on' ? 'true' : 'false')) . "\")";
					$sql[3] = "INSERT INTO config (name,value) VALUES (\"smtpUser\",\"" . mysql_real_escape_string($_POST['smtpUser']) . "\")";
					$sql[4] = "INSERT INTO config (name,value) VALUES (\"smtpPass\",\"" . mysql_real_escape_string($_POST['smtpPass']) . "\")";
					$sql[5] = "INSERT INTO config (name,value) VALUES (\"siteName\",\"" . mysql_real_escape_string($_POST['siteName']) . "\")";
					$sql[6] = "INSERT INTO config (name,value) VALUES (\"xmlrpcURL\",\"" . mysql_real_escape_string($_POST['xmlrpcURL']) . "\")";
					$sql[7] = "INSERT INTO config (name,value) VALUES (\"owner\",\"" . mysql_real_escape_string($_POST['adminUser']) . "\")";
					$sql[8] = "INSERT INTO config (name,value) VALUES (\"verIDBufferSize\",\"100\")";
					$sql[9] = "INSERT INTO config (name,value) VALUES (\"softwareVersion\",\"" . $softwareVersion . "\")";
					$sql[10] = "INSERT INTO config (name,value) VALUES (\"databaseVersion\",\"1\")";
					$sql[11] = "INSERT INTO users (username, password, email, ip) VALUES (\"" . mysql_real_escape_string($_POST['adminUser']) . "\",\"" . mysql_real_escape_string(md5($_POST['adminPass'])) . "\",\"" . mysql_real_escape_string($_POST['adminEmail']) . "\",\"" . mysql_real_escape_string($_SERVER['REMOTE_ADDR']) . "\")";
					$sql[12] = "INSERT INTO centralServers (url, code, xmlrpc) VALUES (\"" . mysql_real_escape_string('central.fourisland.com') . "\",\"" . mysql_real_escape_string(md5('central.fourisland.com')) . "\",\"" . mysql_real_escape_string('http://central.fourisland.com/xmlrpc.php') . "\")";
					$sql[13] = "INSERT INTO subscriptions (username, url, owner, category) VALUES (\"" . mysql_real_escape_string($_POST['adminUser']) . "\", \"" . mysql_real_escape_string('http://fourisland.com/' . $_SERVER['SERVER_NAME'] . '/') . "\", \"true\", \"instadisc\")";

					foreach ($sql as $name => $value)
					{
						if (!trim($value) == '')
						{
							$sql2 = @mysql_query($value);
							if (!$sql2)
							{
								addError($numOfErrors, $errors, '', "MySQL error \"" . mysql_error() . "\" while filling database");
							}
						}
					}

					if ($numOfErrors > 0)
					{
						showHeader('2');
						showStepTwo($_POST['mailDomain'], $_POST['smtpHost'], ($_POST['smtpAuth'] == 'on' ? ' CHECKED' : ''), $_POST['smtpUser'], $_POST['smtpPass'], $_POST['siteName'], $_POST['xmlrpcURL'], $_POST['adminUser'], $_POST['adminPass'], $_POST['adminEmail'], $errors);
					} else {
						showHeader('3');
						showStepThree();
					}
				}
			}

			break;
	}
}

?><P><CENTER><SMALL><SMALL>InstaDisc (C) Starla Insigna 2008. InstaDisc Setup uses the UniForm form theme</SMALL></SMALL></CENTER></BODY></HTML><?php

function showHeader($number)
{
?><HTML><HEAD><TITLE>InstaDisc Server Setup Step <?php echo($number); ?></TITLE><LINK REL="stylesheet" TYPE="text/css" HREF="uniform.css"></HEAD><BODY><CENTER><H1>InstaDisc Installation</H1></CENTER><P><?php
}

function showStepOne($host, $username, $password, $dbname, $errors)
{
?>Welcome to the InstaDisc Central Server installation! Please input your database details below.<P>
<FORM CLASS="uniform" ACTION="./install.php?submit=1" METHOD="POST">
<?php
	if (isset($errors[1]))
	{
?><DIV ID="errorMsg">Uh oh! Validation errors!<P>
<OL><?php
		foreach ($errors as $name => $value)
		{
?><LI><A HREF="#error<?php echo($name); ?>"><?php echo($value['msg']); ?></A></LI><?php
		}
?></OL></DIV><?php
	}
?>
<FIELDSET CLASS="inlineLabels"><LEGEND>Database Details</LEGEND>
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'host'); ?>">
<?php doErrors($errors, 'host'); ?> <LABEL FOR="host"><EM>*</EM> Host: </LABEL>
 <INPUT TYPE="text" ID="host" NAME="host" CLASS="textInput" VALUE="<?php echo($host); ?>">
</DIV>
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'username'); ?>">
<?php doErrors($errors, 'username'); ?> <LABEL FOR="username"><EM>*</EM> Username: </LABEL>
 <INPUT TYPE="text" ID="username" NAME="username" CLASS="textInput" VALUE="<?php echo($username); ?>">
</DIV>
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'password'); ?>">
<?php doErrors($errors, 'password'); ?> <LABEL FOR="password"><EM>*</EM> Password: </LABEL>
 <INPUT TYPE="password" ID="password" NAME="password" CLASS="textInput" VALUE="<?php echo($password); ?>">
</DIV>
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'dbname'); ?>">
<?php doErrors($errors, 'dbname'); ?> <LABEL FOR="dbname"><EM>*</EM> Name: </LABEL>
 <INPUT TYPE="text" ID="dbname" NAME="dbname" CLASS="textInput" VALUE="<?php echo($dbname); ?>">
 <P CLASS="formHint">You need to create this database before running this script.</P>
</DIV>
</FIELDSET>
<DIV CLASS="buttonHolder">
 <INPUT TYPE="submit" VALUE="Next">
</DIV></FORM><?php
}

function showStepTwo($mailDomain, $smtpHost, $smtpAuth, $smtpUser, $smtpPass, $siteName, $xmlrpcURL, $adminUser, $adminPass, $adminEmail, $errors)
{
?>Your database has been set up. All we need to do now is fill it up a little. Please answer the below questions to set up your configuration:
<FORM CLASS="uniform" ACTION="./install.php?submit=2" METHOD="POST">
<?php
	if (isset($errors[1]))
	{
?><DIV ID="errorMsg">Uh oh! Validation errors!<P>
<OL><?php
		foreach ($errors as $name => $value)
		{
?><LI><A HREF="#error<?php echo($name); ?>"><?php echo($value['msg']); ?></A></LI><?php
		}
?></OL></DIV><?php
	}
?>
<FIELDSET CLASS="inlineLabels"><LEGEND>Email</LEGEND>
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'mailDomain'); ?>">
<?php doErrors($errors, 'mailDomain'); ?> <LABEL FOR="mailDomain"><EM>*</EM> Mail Domain: </LABEL>
 <INPUT TYPE="text" ID="mailDomain" NAME="mailDomain" CLASS="textInput" VALUE="<?php echo($mailDomain); ?>">
 <P CLASS="formHint">Type in the part that comes after the @ in your email addresses. This is used when InstaDisc needs to send an email to someone.</P>
</DIV>
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'smtpHost'); ?>">
<?php doErrors($errors, 'smtpHost'); ?> <LABEL FOR="smtpHost"><EM>*</EM> SMTP Host: </LABEL>
 <INPUT TYPE="text" ID="smtpHost" NAME="smtpHost" CLASS="textInput" VALUE="<?php echo($smtpHost); ?>">
 <P CLASS="formHint">This is required because InstaDisc has to be able to send emails to people.</P>
</DIV>
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'smtpAuth'); ?>">
<?php doErrors($errors, 'smtpAuth'); ?> <LABEL FOR="smtpAuth">SMTP Authentication Required? </LABEL>
 <INPUT TYPE="checkbox" ID="smtpAuth" NAME="smtpAuth" CLASS="textInput"<?php echo($smtpAuth); ?>">
 <P CLASS="formHint">If your SMTP server requires authentication (most do), you need to check this box and enter the authentication details in the fields below.</P>
</DIV>
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'smtpUser'); ?>">
<?php doErrors($errors, 'smtpUser'); ?> <LABEL FOR="smtpUser">SMTP Username: </LABEL>
 <INPUT TYPE="text" ID="smtpUser" NAME="smtpUser" CLASS="textInput" VALUE="<?php echo($smtpUser); ?>">
</DIV>
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'smtpPass'); ?>">
<?php doErrors($errors, 'smtpPass'); ?> <LABEL FOR="smtpPass">SMTP Password: </LABEL>
 <INPUT TYPE="password" ID="smtpPass" NAME="smtpPass" CLASS="textInput" VALUE="<?php echo($smtpPass); ?>">
 <P CLASS="formHint">The two above fields only need be filled out if the "SMTP Authentication Required?" box is checked.</P>
</DIV>
</FIELDSET><FIELDSET CLASS="inlineLabels"><LEGEND>Website</LEGEND>
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'siteName'); ?>">
<?php doErrors($errors, 'siteName'); ?> <LABEL FOR="siteName"><EM>*</EM> Site Name: </LABEL>
 <INPUT TYPE="text" ID="siteName" NAME="siteName" CLASS="textInput" VALUE="<?php echo($siteName); ?>">
 <P CLASS="formHint">Your website's name is required for a little personalization of emails.</P>
</DIV>
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'xmlrpcURL'); ?>">
<?php doErrors($errors, 'xmlrpcURL'); ?> <LABEL FOR="xmlrpcURL"><EM>*</EM> XML-RPC URL: </LABEL>
 <INPUT TYPE="text" ID="xmlrpcURL" NAME="xmlrpcURL" CLASS="textInput" VALUE="<?php echo($xmlrpcURL); ?>">
 <P CLASS="formHint">What is the URL of the xmlrpc.php file provided for you in the InstaDisc package?</P>
</DIV>
</FIELDSET><FIELDSET CLASS="inlineLabels"><LEGEND>Administrator's Account</LEGEND>
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'adminUser'); ?>">
<?php doErrors($errors, 'adminUser'); ?> <LABEL FOR="adminUser"><EM>*</EM> Admin Username: </LABEL>
 <INPUT TYPE="text" ID="adminUser" NAME="adminUser" CLASS="textInput" VALUE="<?php echo($adminUser); ?>">
</DIV>
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'adminPass'); ?>">
<?php doErrors($errors, 'adminPass'); ?> <LABEL FOR="adminPass"><EM>*</EM> Admin Password: </LABEL>
 <INPUT TYPE="password" ID="adminPass" NAME="adminPass" CLASS="textInput" VALUE="<?php echo($adminPass); ?>">
</DIV>
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'adminEmail'); ?>">
<?php doErrors($errors, 'adminEmail'); ?> <LABEL FOR="adminEmail"><EM>*</EM> Admin Email: </LABEL>
 <INPUT TYPE="text" ID="adminEmail" NAME="adminEmail" CLASS="textInput" VALUE="<?php echo($adminEmail); ?>">
 <P CLASS="formHint">You, the administrator, must have an account on your InstaDisc server to be able to edit configuration values (mostly the ones you just entered) at will.</P>
</DIV>
</FIELDSET>
<DIV CLASS="buttonHolder">
 <INPUT TYPE="submit" VALUE="Next">
</DIV></FORM><?php
}

function showStepThree()
{
?>Congradulations! You've successfully set up your InstaDisc Central Server's database! Now, the next step for you is to implement the functions in instadisc.php into your web application. See <A HREF="http://fourisland.com/projects/instadisc/wiki/BecomingACentralServer">Becoming A Central Server</A>. Also, it would be smart to subscribe to your InstaDisc Update Notice Subscription, which will notify you if your Central Server's software gets out of date. It's the subscription.php file in this directory. Please subscribe to it, thanks!<?php
}

function ifErrors($errors, $id)
{
	foreach ($errors as $name => $value)
	{
		if ($value['field'] == $id)
		{
			echo(' error');
			return;
		}
	}
}


function doErrors($errors, $id)
{
	foreach ($errors as $name => $value)
	{
		if ($value['field'] == $id)
		{
?> <P ID="error<?php echo($name); ?>" CLASS="errorField"><EM>*</EM> <?php echo($value['msg']); ?></P><?php echo("\n");
		}
	}
}

function addError(&$numOfErrors, &$errors, $field, $msg)
{
	$numOfErrors++;
	$errors[$numOfErrors] = array('field' => $field, 'msg' => $msg);
}
