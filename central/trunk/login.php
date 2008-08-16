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

function showForm($username, $password, $errors)
{
	$template = new FITemplate('login');
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
