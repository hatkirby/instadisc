<?php

/* InstaDisc Server - A Four Island Project */

include('instadisc.php');
include('template.php');

$template = new FITemplate('index');
$template->add('SITENAME', instaDisc_getConfig('siteName'));
$template->display();

?>
