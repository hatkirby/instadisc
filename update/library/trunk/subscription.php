<?php

/* InstaDisc Update - A Four Island Project */

include('instadisc.php'); // Make sure that if you move me away from instadisc.php that you update this include!

echo('Subscription: ' . $idusSubscriptionURI . "\n");
echo('Title: ' . $idusSubscriptionTitle . "\n");
echo('Category: ' . $idusSubscriptionCategory . "\n");

if ($idusActivationKey != '')
{
	echo('Key: ' . $idusActivationKey . "\n");
}

?>
