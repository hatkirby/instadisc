<?php

/* InstaDisc Server - A Four Island Project */

include('includes/instadisc.php');
include('includes/template.php');

if (isset($_SESSION['username']))
{
	if (!isset($_GET['submit']))
	{
		showForm('',array());
	} else {
		$numOfErrors = 0;
		$errors = array();

		$getpending = "SELECT * FROM pending2 WHERE username = \"" . mysql_real_escape_string($_SESSION['username']) . "\" AND url = \"" . mysql_real_escape_string($_POST['url']) . "\"";
		$getpending2 = mysql_query($getpending);
		$getpending3 = mysql_fetch_array($getpending2);
		if ($getpending3['username'] != $_POST['username'])
		{
			addError($numOfErrors, $errors, 'url', 'Subscription could not be found');
		}

		if ($numOfErrors > 0)
		{
			showForm($_POST['url'], $errors);
		} else {
			if ($_POST['submit'] == "Verify")
			{
				switch (instaDisc_addSubscription($_SESSION['username'], $_POST['url']))
				{
					case 0:
						$template = new FITemplate('activatedsub');
						$template->add('SITENAME', instaDisc_getConfig('siteName'));
						$template->display();
						break;

					case 1:
						addError($numOfErrors, $errors, '', 'Unknown error');
						showForm($_POST['url'], $errors);
						break;

					case 2:
						addError($numOfErrors, $errors, 'url', 'Subscription could not be found');
						showForm($_POST['url'], $errors);
						break;

					case 3:
						addError($numOfErrors, $errors, '', 'Subscription File is not well-formed');
						showForm($_POST['url'], $errors);
						break;

					case 4:
						addError($numOfErrors, $errors, '', 'Key in Subscription File is incorrect');
						showForm($_POST['url'], $errors);
						break;
				}
			} else {
				instaDisc_cancelSubscription($_SESSION['username'], $_POST['url']);
			}
		}
	}
} else {
	header('Location: index.php');
}

function showForm($url, $errors)
{
	$template = new FITemplate('activatesub');
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
