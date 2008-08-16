<?php

/* InstaDisc Server - A Four Island Project */

include('includes/instadisc.php');
include('includes/template.php');

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
			$template = new FITemplate('registered');
			$template->add('SITENAME', instaDisc_getConfig('siteName'));
			$template->display();
		} else {
			addError($numOfErrors, $errors, '', $send);
			showForm($_POST['username'], $_POST['password'], $_POST['email'], $errors);
		}
	}
}

function showForm($username, $password, $email, $errors)
{
	$template = new FITemplate('register');
	$template->add('SITENAME', instaDisc_getConfig('siteName'));

	if (isset($errors[1]))
	{
		$template->adds_block('ERROR', array('ex'=>'1'));

		foreach ($errors as $name => $value)
		{
			$template->adds_block('ERRORS', array(	'NAME' => $name,
								'MSG' => $value['msg']));
		}
	}

	$template->add('USERNAME_ERR', ifErrors($errors, 'username'));
	$template->add('PASSWORD_ERR', ifErrors($errors, 'password'));
	$template->add('EMAIL_ERR', ifErrors($errors, 'email'));

	doErrors($template, $errors, 'username');
	doErrors($template, $errors, 'password');
	doErrors($template, $errors, 'email');

	$template->add('USERNAME', $username);
	$template->add('PASSWORD', $password);
	$template->add('EMAIL', $email);

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
			$template->adds_block(strtoupper($id) . '_ERRS', array(	'NAME' => $name,
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
