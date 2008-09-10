<?php

/* InstaDisc Series - A Four Island Project */

include('includes/instadisc.php');

$template = new FITemplate('index');
$template->add('SITENAME', instaDisc_getConfig('siteName'));

$subs = instaDisc_getAllSubscriptions();
foreach ($subs as $name => $value)
{
	if ($value['personal'] == 'false')
	{
		$template->adds_block('SUBSCRIPTIONS', array(	'IDENTITY' => $value['identity'],
								'TITLE' => $value['title'],
								'CATEGORY' => $value['category']));
	}
}

$template->display();

?>
