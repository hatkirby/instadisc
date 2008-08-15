<?php

/* InstaDisc Server - A Four Island Project */

include('instadisc.php');
include('template.php');

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
				$template = new FITemplate('activated');
				$template->add('SITENAME', instaDisc_getConfig('siteName'));
				$template->display();
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
	$template = new FITemplate('activate');
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
	$template->add('CODE_ERR', ifErrors($errors, 'code'));

	doErrors($template, $errors, 'username');
	doErrors($template, $errors, 'code');

	$template->add('USERNAME', $username);
	$template->add('CODE', $code);

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
