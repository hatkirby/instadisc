<?php

/* InstaDisc Server - A Four Island Project */

include('includes/instadisc.php');
include('includes/template.php');

$template = new FITemplate('index');
$template->add('SITENAME', instaDisc_getConfig('siteName'));
$template->display();

?>
