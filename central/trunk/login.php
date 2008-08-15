<?php

/* InstaDisc Server - A Four Island Project */

include('includes/instadisc.php');
include('includes/template.php');

if (!isset($_GET['submit']))
{
	showForm('','',array());
} else {
	$numOfErrors = 0;
	$errors = array();

	$getuser = "SELECT * FROM users WHERE username = \"" . mysql_real_escape_string($_POST['username']) . "\" AND password = \"" . mysql_real_escape_string(md5($_POST['password'])) . "\"";
	$getuser2 = mysql_query($getuser);
	$getuser3 = mysql_fetch_array($getuser2);
	if ($getuser3['username'] != $_POST['username'])
	{
		addError($numOfErrors, $errors, '', 'Account could not be found');
	}

	if ($numOfErrors > 0)
	{
		showForm($_POST['username'], $_POST['password'], $errors);
	} else {
		if (instaDisc_verifyUser($_POST['username'], $_POST['password']))
		{
			$_SESSION['username'] == $_POST['username'];

			$template = new FITemplate('loggedin');
			$template->add('SITENAME', instaDisc_getConfig('siteName'));
			$template->display();
		} else {
			addError($numOfErrors, $errors, '', 'Account could not be found');
			showForm($_POST['username'], $_POST['password'], $errors);
		}
	}
}

function showForm($username, $password, $errors)
{
	$template = new FITemplate('login');
	$template->add('SITENAME', instaDisc_getConfig('siteName'));

	if (isset($errors[1]))
	{
		$template->adds('ERROR', array('ex'=>'1'));

		foreach ($errors as $name => $value)
		{
			$template->adds('ERRORS', array(	'NAME' => $name,
								'MSG' => $value['msg']));
		}
	}

	$template->add('USERNAME_ERR', ifErrors($errors, 'username'));
	$template->add('PASSWORD_ERR', ifErrors($errors, 'password'));

	doErrors($template, $errors, 'username');
	doErrors($template, $errors, 'password');

	$template->add('USERNAME', $username);
	$template->add('PASSWORD', $password);

	$template->display();
}

function ifErrors($errors, $id)
{
        foreach ($errors as $name => $value)
        {
                if ($value['field'] == $id)
                {
                        return ' error';
                }
        }

	return '';
}

function doErrors($template, $errors, $id)
{
        foreach ($errors as $name => $value)
        {
                if ($value['field'] == $id)
                {
			$template->adds(strtoupper($id) . '_ERRS', array(	'NAME' => $name,
										'VALUE' => $value['msg']));
                }
        }
}

function addError(&$numOfErrors, &$errors, $field, $msg)
{
        $numOfErrors++;
        $errors[$numOfErrors] = array('field' => $field, 'msg' => $msg);
}

?>