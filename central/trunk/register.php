<?php

/* InstaDisc Server - A Four Island Project */

include('instadisc.php');

if (!isset($_GET['submit']))
{
	showForm('','','',array());
} else {
	$numOfErrors = 0;
	$errors = array();

	if ($_POST['username'] == '')
	{
		addError($numOfErrors, $errors, 'username', 'Username is a required field');
	}

	if ($_POST['password'] == '')
	{
		addError($numOfErrors, $errors, 'password', 'Password is a required field');
	}

	if ($_POST['email'] == '')
	{
		addError($numOfErrors, $errors, 'email', 'Email is a required field');
	}

	if ($numOfErrors > 0)
	{
		showForm($_POST['username'], $_POST['password'], $_POST['email'], $errors);
	} else {
		$send = instaDisc_sendActivationEmail($_POST['username'], $_POST['password'], $_POST['email']);
		if ($send === TRUE)
		{
?>
<HTML>
 <HEAD>
  <TITLE><?php echo(instaDisc_getConfig('siteName')); ?> InstaDisc Central Server</TITLE>
 </HEAD>
 <BODY>
  <CENTER>
   <H1>InstaDisc Registration</H1>

   <P>Thank you for registering! An activation email has been sent to the address you provided. When you recieve it, copy the 
    code inside to the <A HREF="activate.php">Activation page</A>.
  </CENTER>
 </BODY>
</HTML>
<?php
		} else {
			addError($numOfErrors, $errors, '', $send);
			showForm($_POST['username'], $_POST['password'], $_POST['email'], $errors);
		}
	}
}

function showForm($username, $password, $email, $errors)
{
?>
<HTML>
 <HEAD>
  <TITLE><?php echo(instaDisc_getConfig('siteName')); ?> InstaDisc Central Server</TITLE>
  <LINK REL="stylesheet" TYPE="text/css" HREF="uniform.css">
 </HEAD>
 <BODY>
  <CENTER>
   <H1>InstaDisc Registration</H1>

   <P>If you would like to sign up for our InstaDisc service, please fill out the form below.
  </CENTER>

  <FORM CLASS="uniform" ACTION="./register.php?submit=" METHOD="POST">
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
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'password'); ?>">
<?php doErrors($errors, 'password'); ?> <LABEL FOR="password"><EM>*</EM> Password: </LABEL>
 <INPUT TYPE="password" ID="password" NAME="password" CLASS="textInput" VALUE="<?php echo($password); ?>">
</DIV>
<DIV CLASS="ctrlHolder<?php ifErrors($errors, 'email'); ?>">
<?php doErrors($errors, 'email'); ?> <LABEL FOR="email"><EM>*</EM> Email: </LABEL>
 <INPUT TYPE="text" ID="email" NAME="email" CLASS="textInput" VALUE="<?php echo($email); ?>">
</DIV>
</FIELDSET>
<DIV CLASS="buttonHolder">
 <INPUT TYPE="submit" VALUE="Submit">
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
