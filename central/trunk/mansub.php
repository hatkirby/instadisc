<?php

/* InstaDisc Server - A Four Island Project */

include('includes/instadisc.php');
include('includes/template.php');

if (isset($_SESSION['username']))
{
	$template = new FITemplate('mansub');
	$template->add('SITENAME', instaDisc_getConfig('siteName'));

	$subs = instaDisc_listSubscriptions($_SESSION['username']);
	$i=0;
	for ($i=0;$i<$subs['size'];$i++)
	{
		$template->adds_block('SUBSCRIPTION', array(	'URL' => $subs[$i],
								'ID' => $i,
								'EVEN' => (($i % 2 == 0) ? " CLASS=\"even\"" : "")));
	}

	$template->display();
} else {
	header('Location: index.php');
}

?>
