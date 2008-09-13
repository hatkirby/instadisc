<?php

/* InstaDisc Series - A Four Island Project */

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
							showStepTwo('', '', '', array());
						}
					}
				}
			}

			break;

		case 2:
			if ($_POST['siteName'] == '')
			{
				addError($numOfErrors, $errors, 'siteName', 'Site Name is a required field');
			}

			if ($_POST['adminUser'] == '')
			{
				addError($numOfErrors, $errors, 'adminUser', 'Administrator Username is a required field');
			}

			if ($_POST['adminPass'] == '')
			{
				addError($numOfErrors, $errors, 'adminPass', 'Administrator Password is a required field');
			}

			if ($numOfErrors > 0)
			{
				showHeader('2');
				showStepTwo($_POST['siteName'], $_POST['adminUser'], $_POST['adminPass'], $errors);
			} else {
				include_once('includes/config.php');

				mysql_connect($dbhost, $dbuser, $dbpass);
				mysql_select_db($dbname);

				$sql[0] = "INSERT INTO config (name,value) VALUES (\"siteName\",\"" . mysql_real_escape_string($_POST['siteName']) . "\")";
				$sql[1] = "INSERT INTO config (name,value) VALUES (\"adminUser\",\"" . mysql_real_escape_string($_POST['adminUser']) . "\")";
				$sql[2] = "INSERT INTO users (username,password) VALUES (\"" . mysql_real_escape_string($_POST['adminUser']) . "\",\"" . mysql_real_escape_string(md5($_POST['adminPass'])) . "\")";

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
					showStepTwo($_POST['siteName'], $_POST['adminUser'], $_POST['adminPass'], $errors);
				} else {
					showHeader('3');
					showStepThree();
				}
			}

		break;
	}
}

?><P><CENTER><SMALL><SMALL>InstaDisc (C) Starla Insigna 2008. InstaDisc Setup uses the UniForm form theme</SMALL></SMALL></CENTER></BODY></HTML><?php

function showHeader($number)
{
?><HTML><HEAD><TITLE>InstaDisc Series Setup Step <?php echo($number); ?></TITLE><LINK REL="stylesheet" TYPE="text/css" HREF="uniform.css"></HEAD><BODY><CENTER><H1>InstaDisc Installation</H1></CENTER><P><?php
}

function showStepOne($host, $username, $password, $dbname, $errors)
{
?>Welcome to the InstaDisc Series Control installation! Please input your database details below.<P>
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

function showStepTwo($siteName, $adminUser, $adminPass, $errors)
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
<FIELDSET CLASS="inlineLabels"><LEGEND>Website</LEGEND>
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'siteName'); ?>">
<?php doErrors($errors, 'siteName'); ?> <LABEL FOR="siteName"><EM>*</EM> Site Name: </LABEL>
 <INPUT TYPE="text" ID="siteName" NAME="siteName" CLASS="textInput" VALUE="<?php echo($siteName); ?>">
 <P CLASS="formHint">Your website's name is required for a little personalization of emails.</P>
</DIV>
</FIELDSET><FIELDSET CLASS="inlineLabels"><LEGEND>Administrator User Details</LEGEND>
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'adminUser'); ?>">
<?php doErrors($errors, 'adminUser'); ?> <LABEL FOR="adminUser"><EM>*</EM> Administrator Username: </LABEL>
 <INPUT TYPE="text" ID="adminUser" NAME="adminUser" CLASS="textInput" VALUE="<?php echo($adminUser); ?>">
</DIV>
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'adminPass'); ?>">
<?php doErrors($errors, 'adminPass'); ?> <LABEL FOR="adminPass"><EM>*</EM> Administrator Password: </LABEL>
 <INPUT TYPE="password" ID="adminPass" NAME="adminPass" CLASS="textInput" VALUE="<?php echo($adminPass); ?>">
</DIV>
</FIELDSET>
<DIV CLASS="buttonHolder">
 <INPUT TYPE="submit" VALUE="Next">
</DIV></FORM><?php
}

function showStepThree()
{
?>Congradulations! You've successfully set up your InstaDisc Series Control!<?php
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
