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

	if ($_POST['old'] == '')
	{
		addError($numOfErrors, $errors, 'old', 'Old Password is a required field');
	} else {
		$getuser = "SELECT * FROM users WHERE username = \"" . mysql_real_escape_string($_SESSION['username']) . "\" AND password = \"" . mysql_real_escape_string(md5($_POST['old'])) . "\"";
		$getuser2 = mysql_query($getuser);
		$getuser3 = mysql_fetch_array($getuser2);

		if ($getuser3['password'] != md5($_POST['password']))
		{
			addError($numOfErrors, $errors, 'old', 'Old password is not correct');
		}
	}

	if ($_POST['new'] == '')
	{
		addError($numOfErrors, $errors, 'new', 'New Password is a required field');
	}

	if ($_POST['confirm'] == '')
	{
		addError($numOfErrors, $errors, 'confirm', 'Confirm New Password is a required field');
	}

	if ($_POST['new'] != $_POST['confirm'])
	{
		addError($numOfErrors, $errors, 'confirm', 'Passwords do not match');
	}

	if ($numOfErrors > 0)
	{
		showForm($_POST['old'], $_POST['new'], $_POST['confirm'], $errors);
	} else {
		instaDisc_changePassword($_SESSION['username'], $_POST['new']);

		$template = new FITemplate('changedpassword');
		$template->add('SITENAME', instaDisc_getConfig('siteName'));
		$template->display();
	}
}

function showForm($old, $new, $confirm, $errors)
{
	$template = new FITemplate('changepassword');
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

	$template->add('OLD_ERR', ifErrors($errors, 'old'));
	$template->add('NEW_ERR', ifErrors($errors, 'new'));
	$template->add('CONFIRM_ERR', ifErrors($errors, 'confirm'));

	doErrors($template, $errors, 'old');
	doErrors($template, $errors, 'new');
	doErrors($template, $errors, 'confirm');

	$template->add('OLD', $old);
	$template->add('NEW', $new);
	$template->add('CONFIRM', $confirm);

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
