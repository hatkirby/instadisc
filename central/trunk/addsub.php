<?php

/* InstaDisc Server - A Four Island Project */

include('includes/instadisc.php');
include('includes/template.php');

if (!isset($_GET['submit']))
{
	showForm('',array());
} else {
	$numOfErrors = 0;
	$errors = array();

	if ($_POST['url'] == '')
	{
		addError($numOfErrors, $errors, 'url', 'URL is a required field');
	}

	if ($numOfErrors > 0)
	{
		showForm($_POST['url'], $errors);
	} else {
		$key = instaDisc_generateSubscriptionActivation($_SESSION['username'], $_POST['url']);
		if ($key !== FALSE)
		{
			$template = new FITemplate('addedsub');
			$template->add('SITENAME', instaDisc_getConfig('siteName'));
			$template->add('KEY', $key);
			$template->display();
		} else {
			addError($numOfErrors, $errors, '', 'Unknown error');
			showForm($_POST['url'], $errors);
		}
	}
}

function showForm($url, $errors)
{
	$template = new FITemplate('addsub');
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

	$template->add('URL_ERR', ifErrors($errors, 'url'));

	doErrors($template, $errors, 'url');

	$template->add('URL', $url);

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
