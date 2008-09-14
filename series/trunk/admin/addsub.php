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
	showForm('','','','','',array());
} else {
	$numOfErrors = 0;
	$errors = array();

	if ($_POST['id'] == '')
	{
		addError($numOfErrors, $errors, 'id', 'Subscription ID is a required field');
	}

	if ($_POST['title'] == '')
	{
		addError($numOfErrors, $errors, 'title', 'Title is a required field');
	}

	if ($_POST['url'] == '')
	{
		addError($numOfErrors, $errors, 'url', 'Subscription URL is a required field');
	}

	if ($_POST['category'] == '')
	{
		addError($numOfErrors, $errors, 'category', 'Category is a required field');
	}

	if ($numOfErrors > 0)
	{
		showForm($_POST['id'], $_POST['title'], $_POST['url'], $_POST['category'], $_POST['password'], $errors);
	} else {
		instaDisc_initSubscription($_SESSION['username'], $_POST['id'], $_POST['url'], $_POST['title'], $_POST['category'], $_POST['password']);

		$template = new FITemplate('addedsub');
		$template->add('SITENAME', instaDisc_getConfig('siteName'));
		$template->display();
	}
}

function showForm($id, $title, $url, $category, $password, $errors)
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

	$template->add('ID_ERR', ifErrors($errors, 'id'));
	$template->add('TITLE_ERR', ifErrors($errors, 'title'));
	$template->add('URL_ERR', ifErrors($errors, 'url'));
	$template->add('CATEGORY_ERR', ifErrors($errors, 'category'));
	$template->add('PASSWORD_ERR', ifErrors($errors, 'password'));

	doErrors($template, $errors, 'id');
	doErrors($template, $errors, 'title');
	doErrors($template, $errors, 'url');
	doErrors($template, $errors, 'category');
	doErrors($template, $errors, 'password');

	$template->add('ID', $id);
	$template->add('TITLE', $title);
	$template->add('URL', $url);
	$template->add('CATEGORY', $category);
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
