<?php

/* InstaDisc Server - A Four Island Project */

include('instadisc.php');

if (!isset($_GET['submit']))
{
	showForm('','',array());
} else {
	$numOfErrors = 0;
	$errors = array();

	$getpending = "SELECT * FROM pending WHERE username = \"" . mysql_real_escape_string($_POST['username']) . "\" AND code = \"" . mysql_real_escape_string($_POST['code']) . "\"";
	$getpending2 = mysql_query($getpending);
	$getpending3 = mysql_fetch_array($getpending2);
	if ($getpending3['username'] != $_POST['username'])
	{
		addError($numOfErrors, $errors, '', 'Account could not be found');
	}

	if ($numOfErrors > 0)
	{
		showForm($_POST['username'], $_POST['code'], $errors);
	} else {
		if ($_POST['submit'] == "Verify")
		{
			if (instaDisc_activateAccount($_POST['username'], $_POST['code']))
			{
?>
<HTML>
 <HEAD>
  <TITLE><?php echo(instaDisc_getConfig('siteName')); ?> InstaDisc Central Server</TITLE>
 </HEAD>
 <BODY>
  <CENTER>
   <H1>InstaDisc Activation</H1>

   <P>Thank you for activating! You've now been signed up for the InstaDisc service.
    You will recieve an email with the information to input into your InstaDisc client.
  </CENTER>
 </BODY>
</HTML>
<?php
			} else {
				addError($numOfErrors, $errors, '', 'The email could not be sent');
				showForm($_POST['username'], $_POST['code'], $errors);
			}
		} else {
			instaDisc_deactivateAccount($_POST['username'], $_POST['code']);
		}
	}
}

function showForm($username, $code, $errors)
{
?>
<HTML>
 <HEAD>
  <TITLE><?php echo(instaDisc_getConfig('siteName')); ?> InstaDisc Central Server</TITLE>
  <LINK REL="stylesheet" TYPE="text/css" HREF="uniform.css">
 </HEAD>
 <BODY>
  <CENTER>
   <H1>InstaDisc Activation</H1>

   <P>If you've already registered and an activation email has been sent to your address, please fill in the form below.
  </CENTER>

  <FORM CLASS="uniform" ACTION="./activate.php?submit=" METHOD="POST">
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
<FIELDSET CLASS="inlineLabels"><LEGEND>User Details</LEGEND>
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'username'); ?>">
<?php doErrors($errors, 'username'); ?> <LABEL FOR="username"><EM>*</EM> Username: </LABEL>
 <INPUT TYPE="text" ID="username" NAME="username" CLASS="textInput" VALUE="<?php echo($username); ?>">
</DIV>
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'code'); ?>">
<?php doErrors($errors, 'code'); ?> <LABEL FOR="code"><EM>*</EM> Activation Code: </LABEL>
 <INPUT TYPE="text" ID="code" NAME="code" CLASS="textInput" VALUE="<?php echo($code); ?>">
</DIV>
</FIELDSET>
<DIV CLASS="buttonHolder">
 <INPUT TYPE="submit" NAME="submit" VALUE="Verify">
 <INPUT TYPE="submit" NAME="submit" VALUE="Delete">
</DIV></FORM><?php
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
