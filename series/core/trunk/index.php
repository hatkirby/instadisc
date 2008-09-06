<?php

/* InstaDisc Series - A Four Island Project */

include('includes/instadisc.php');

$template = new FITemplate('index');

$subs = instaDisc_getAllSubscriptions();
foreach ($subs as $name => $value)
{
	if ($value['personal'] == 'false')
	{
		$template->adds_block('SUBSCRIPTION', array(	'IDENTITY' => $name,
								'TITLE' => $value['title'],
								'CATEGORY' => $value['category']));
	}
}

$template->display();

?>
