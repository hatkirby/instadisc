<?php

/* InstaDisc Series - A Four Island Project */

/** 
 * require_once() is used to ensure
 * the ACP files are being called by
 * admin.php instead of their actual
 * locations admin/.
 * The _once() part ensures no problem
 * arises as includes/instadisc.php has
 * already been included from admin.php
 */
require_once('includes/instadisc.php');

if (!isset($_SESSION['username']))
{
	header('Location: index.php');
	exit;
}

if (!isset($_GET['submit']))
{
	showForm('','',array());
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

	if ($numOfErrors > 0)
	{
		showForm($_POST['username'], $_POST['password'], $errors);
	} else {
		instaDisc_addUser($_POST['username'], $_POST['password']);

		$template = new FITemplate('addeduser');
		$template->add('SITENAME', instaDisc_getConfig('siteName'));
		$template->display();
	}
}

function showForm($username, $password, $errors)
{
	$template = new FITemplate('adduser');
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
