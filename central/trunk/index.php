<?php

/* InstaDisc Server - A Four Island Project */

include('includes/common.php');

$template = new FITemplate('index');
$template->add('TITLE', $config['title']);
$template->add('TEXT', $config['text']);
$template->display();

include('includes/footer.php');

?>
